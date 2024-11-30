<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stock tidak mencukupi');
        }

        $total_amount = $product->price * $request->quantity;
        $order_id = 'TRX-' . time();

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'order_id' => $order_id,
            'total_amount' => $total_amount,
            'status' => 'pending'
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $total_amount
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);

            return view('transactions.payment', compact('snapToken', 'transaction'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $transaction = Transaction::where('order_id', $request->order_id)->first();

            if ($request->transaction_status == 'capture' || $request->transaction_status == 'Settlement') {
                $transaction->update(['status' => 'paid']);

                // Update stock
                foreach ($transaction->items as $item) {
                    $product = $item->product;
                    $product->update([
                        'stock' => $product->stock - $item->quantity
                    ]);
                }
            } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'expire') {
                $transaction->update(['status' => 'cancelled']);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function updatePaymentStatus(Request $request)
    {
        $transaction = Transaction::where('order_id', $request->order_id)->first();

        if ($transaction) {
            $transaction->update(['status' => 'paid']);

            // Update stock
            foreach ($transaction->items as $item) {
                $product = $item->product;
                $product->update([
                    'stock' => $product->stock - $item->quantity
                ]);
            }

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 404);
    }


    public function index()
    {
        // Mengambil semua transaksi untuk pengguna yang sedang login
        $transactions = Transaction::with('items.product')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengembalikan view dengan data transaksi
        return view('transactions.history', compact('transactions'));
    }
}
