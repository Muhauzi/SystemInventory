<?php

namespace App\Http\Controllers;

use App\Mail\TagihanPenggantian;
use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use App\Models\FotoKerusakan;
use App\Models\TagihanKerusakan;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class LaporanKerusakanController extends Controller
{
    private $ModelLaporan, $ModelPeminjaman, $ModelDetail, $ModelInventaris, $ModelFotoKerusakan;


    public function __construct()
    {
        $this->ModelLaporan = new LaporanKerusakan();
        $this->ModelPeminjaman = new Peminjaman();
        $this->ModelDetail = new DetailPeminjaman();
        $this->ModelInventaris = new Inventaris();
        $this->ModelFotoKerusakan = new FotoKerusakan();
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
    }

    public function index()
    {
        $laporan_kerusakan   = $this->ModelLaporan->getBarangKategori();
        // dd( $laporan_kerusakan);
        $tagihan = TagihanKerusakan::all();
        return view('laporan.kerusakan.index', compact('laporan_kerusakan', 'tagihan'));
    }

    public function detailKerusakan($id)
    {
        $laporan_kerusakan = $this->ModelLaporan->getDetailKerusakan($id);
        $tagihan = TagihanKerusakan::where('id_laporan_kerusakan', $id)->first();
        $bukti_kerusakan = $this->ModelFotoKerusakan->where('id_laporan_kerusakan', $id)->get()->toArray();
        return view('laporan.kerusakan.show', compact('laporan_kerusakan', 'bukti_kerusakan', 'tagihan'));
    }

    public function konfirmasiPenggantian($id)
    {
        $laporan_kerusakan = LaporanKerusakan::findOrFail($id);
        $detail_peminjaman = DetailPeminjaman::where('id', $laporan_kerusakan->id_detail_peminjaman)->first();
        $inventaris = Inventaris::where('id', $detail_peminjaman->id_inventaris)->first();
        return view('laporan_kerusakan.konfirmasi_penggantian', compact('laporan_kerusakan', 'detail_peminjaman', 'inventaris'));
    }

    public function formSubmitKerusakan($id)
    {
        $PeminjamanModel = new Peminjaman();
        $id_detail = DetailPeminjaman::where('id_peminjaman', $id)->first();
        $ModelDetail = new DetailPeminjaman();
        $dataPeminjam = $ModelDetail->getDetail($id_detail->id);

        return view('laporan.kerusakan.form_submit', compact('dataPeminjam'));
    }

    public function submitKerusakan(Request $request)
    {
        $data = $request->all();
        $modelLaporanKerusakan = new LaporanKerusakan();
        $modelFotoKerusakan = new FotoKerusakan();

        $modelLaporanKerusakan->create([
            'id_detail_peminjaman' => $data['id_detail_peminjaman'],
            'deskripsi_kerusakan' => $data['deskripsi_kerusakan'],
        ]);

        $id_laporan_kerusakan = $modelLaporanKerusakan->latest()->first()->id;

        if ($request->hasFile('foto_kerusakan')) {
            $files = $request->file('foto_kerusakan');
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $file->storePublicly('buktiKerusakan', 'public');
                $modelFotoKerusakan->create([
                    'id_laporan_kerusakan' => $id_laporan_kerusakan,
                    'foto' => $filename,
                ]);
            }
        }

        return redirect()->route('peminjaman.index')->with('success', 'Laporan kerusakan berhasil dibuat.');
    }

    public function editKerusakan($id)
    {
        $laporan_kerusakan = $this->ModelLaporan->getDetailKerusakan($id);
        return view('laporan.kerusakan.edit', compact('laporan_kerusakan'));
    }

    public function updateKerusakan(Request $request, $id)
    {
        $data = $request->all();
        $modelLaporanKerusakan = new LaporanKerusakan();
        $modelFotoKerusakan = new FotoKerusakan();

        $modelLaporanKerusakan->where('id', $id)->update([
            'deskripsi_kerusakan' => $data['deskripsi_kerusakan'],
        ]);

        $id_laporan_kerusakan = $id;

        if ($request->hasFile('foto_kerusakan')) {
            $files = $request->file('foto_kerusakan');
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $file->storePublicly('buktiKerusakan', 'public');
                $modelFotoKerusakan->create([
                    'id_laporan_kerusakan' => $id_laporan_kerusakan,
                    'foto' => $filename,
                ]);
            }
        }

        return redirect()->route('laporan_kerusakan.index');
    }

    public function createTagihan($id)
    {
        $laporan_kerusakan = LaporanKerusakan::findOrFail($id);
        $detail_peminjaman = DetailPeminjaman::where('id', $laporan_kerusakan->id_detail_peminjaman)->first();
        $inventaris = Inventaris::where('id', $detail_peminjaman->id_inventaris)->first();
        return view('laporan.kerusakan.create_tagihan', compact('laporan_kerusakan', 'detail_peminjaman', 'inventaris'));
    }

    public function storeTagihan(Request $request)
    {
        $request->validate([
            'id_lk' => 'required|exists:laporan_kerusakan,id',
            'biaya_perbaikan' => 'required|numeric',
        ]
        , [
            'id_lk.required' => 'ID Laporan Kerusakan harus diisi.',
            'id_lk.exists' => 'ID Laporan Kerusakan tidak ditemukan.',
            'biaya_perbaikan.required' => 'Biaya perbaikan harus diisi.',
            'biaya_perbaikan.numeric' => 'Biaya perbaikan harus berupa angka.',
        ]);

        $idLK = $request->id_lk;  
        $total_harga = $request->biaya_perbaikan;
        $modelTagihan = new TagihanKerusakan();
        if ($idLK = $modelTagihan->where('id_laporan_kerusakan', $idLK)->first()) {
            return redirect()->back()->with('error', 'Tagihan sudah dibuat.');
        }
        $dataInput = ([
            'id_laporan_kerusakan' => $request->id_lk,
            'total_tagihan' => $total_harga,
            'status' => 'Belum Lunas',
        ]);
        // dd($data);
        $modelTagihan->create($dataInput);
        $idTagihan = $modelTagihan->latest()->first()->id;
        $model_laporan = new LaporanKerusakan();
        $data = $model_laporan->getPeminjaman($request->id_lk);
        $params = [
            'transaction_details' => [
                'order_id' => $idTagihan,
                'gross_amount' => $total_harga,
            ],
            'customer_details' => [
                'first_name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
            ],
        ];
        $paymentUrl = Snap::createTransaction($params)->redirect_url;
        $snapToken = $snapToken = Snap::getSnapToken($params);
        // dd($paymentUrl, $params);

        $modelTagihan->where('id', $idTagihan)->update([
            'token' => $snapToken,
            'payment_url' => $paymentUrl,
        ]);

        $this->sendEmailPenagihan($idTagihan);

        return redirect()->route('laporan_kerusakan.index')->with('success', 'Tagihan berhasil dibuat.');
    }

    public function webhooks(Request $request)
    {
        $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth",
        ])->get("https://api.sandbox.midtrans.com/v2/$request->order_id/status");


        $response = json_decode($response->body());

        $tagihan = TagihanKerusakan::where('id', $request->order_id)->first();

        if ($tagihan->status == 'settlement' || $tagihan->status == 'capture') {
            return response()->json('Payment has been already processed');
        }

        if ($response->transaction_status == 'capture') {
            $tagihan->status = 'capture';
        } elseif ($response->transaction_status == 'settlement') {
            $tagihan->status = 'settlement';
        } elseif ($response->transaction_status == 'pending') {
            $tagihan->status = 'pending';
        } elseif ($response->transaction_status == 'deny') {
            $tagihan->status = 'deny';
        } elseif ($response->transaction_status == 'cancel') {
            $tagihan->status = 'cancel';
        } elseif ($response->transaction_status == 'expire') {
            $tagihan->status = 'expire';
        } elseif ($response->transaction_status == 'refund') {
            $tagihan->status = 'refund';
        } else {
            $tagihan->status = 'error';
        }
        $tagihan->save();

        return response()->json(['status' => 'success']);
    }

    public function sendEmailPenagihan($id)
    {
        $tagihanModel = new TagihanKerusakan();
        $tagihan = $tagihanModel->getLaporanPeminjaman($id);
        // dd($tagihan);
        Mail::to($tagihan->email)->send(new TagihanPenggantian($tagihan));

        return response()->json(['status' => 'Email sent successfully']);
    }

    public function getLaporanKerusakan()
    {
        $laporan_kerusakan = $this->ModelLaporan->getBarangKategori();
        return response()->json($laporan_kerusakan);
    }
}
