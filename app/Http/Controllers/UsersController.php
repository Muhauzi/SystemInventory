<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Peminjaman;
use App\Models\TagihanKerusakan;
use App\Models\detailPeminjaman;
use App\Models\Inventaris;
use App\Models\DetailUsers;
use App\Models\M_detail_pengajuan;
use App\Models\M_pengajuan;
use App\Models\m_tagihan;
use Illuminate\Support\Facades\Log;
use App\Mail\NotifikasiPengajuan;


use App\Services\MidtransService;
use Midtrans\Transaction;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    protected   $m_peminjaman,
        $m_barang,
        $m_detail_pengajuan,
        $m_pengajuan,
        $m_tagihan,
        $m_user,
        $m_tagihan_kerusakan;
    protected $auth, $authUser;

    public function __construct()
    {
        $this->m_peminjaman = new Peminjaman(); // Inisialisasi model peminjaman
        $this->m_barang = new Inventaris(); // Inisialisasi model barang
        $this->auth = new Auth(); // Inisialisasi auth
        $this->authUser = Auth::user(); // Mengambil data user yang sedang login
        $this->m_detail_pengajuan = new M_detail_pengajuan(); // Inisialisasi model detail pengajuan
        $this->m_pengajuan = new M_pengajuan(); // Inisialisasi model pengajuan
        $this->m_tagihan = new m_tagihan(); // Inisialisasi model tagihan kerusakan
        $this->m_tagihan_kerusakan = new TagihanKerusakan(); // Inisialisasi model tagihan kerusakan
        $this->m_user = new User(); // Inisialisasi model user

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }
    public function index() // Menampilkan data user
    {
        return view('user.index', compact('detail')); // Menampilkan data user
    }

    public function show()  // Menampilkan profile user
    {
        $profile = User::find(Auth::user()->id);
        $detail = DetailUsers::where('user_id', Auth::user()->id)->get()->first();

        return view('user.profile', compact('profile', 'detail')); // Menampilkan profile user
    }

    public function updateProfile(Request $request) // Mengubah data profile user
    {

        $user = User::find(Auth::user()->id);   // Mengambil data user berdasarkan id
        $user->name = $request->name;   // Menambahkan data nama dari form
        $user->email = $request->email; // Menambahkan data email dari form


        $detail = DetailUsers::where('user_id', Auth::user()->id)->first(); // Mengambil data detail user berdasarkan id user

        $foto = $request->file('foto'); // Menambahkan data foto dari form
        if ($request->hasFile('foto')) {    // Jika ada file foto
            $foto->move('profileImages', $foto->getClientOriginalName());   // Pindahkan foto ke folder profileImages
            $namaFoto = $foto->getClientOriginalName();  // Menambahkan data profile_image dari form
        } else {
            $namaFoto = $detail->profile_image; // Jika tidak ada file foto, gunakan foto lama
        }

        $data = [
            'phone' => $request->phone,
            'department' => $request->department,
            'address' => $request->address,
            'about' => $request->about,
            'profile_image' => $namaFoto,
        ];   // Menyimpan data detail user

        if ($detail) {
            $detail->update($data); // Mengupdate data detail user
        } else {
            DetailUsers::create(array_merge($data, ['user_id' => Auth::user()->id])); // Membuat data detail user baru jika belum ada
        }

        $user->save();  // Menyimpan data user

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword(Request $request)    // Mengubah password user
    {
        $request->validate(
            [
                'current_password' => 'required',
                'new_password' => 'required|min:8',
            ],
            [
                'current_password.required' => 'Password lama harus diisi.',
                'new_password.required' => 'Password baru harus diisi.',
                'new_password.min' => 'Password minimal 8 karakter.',
            ]
        ); // Validasi inputan

        $user = User::find(Auth::user()->id);   // Mengambil data user berdasarkan id

        if (password_verify($request->current_password, $user->password)) {   // Jika password lama sesuai
            if ($request->current_password == $request->new_password) {    // Jika password lama sama dengan password baru
                return redirect()->back()->with('error', 'Password baru tidak boleh sama dengan password lama.');
            } elseif ($request->new_password != $request->confirm_password) {    // Jika password baru tidak sama dengan konfirmasi password baru
                return redirect()->back()->with('error', 'Konfirmasi password baru tidak sesuai.');
            } else {
                $user->password = bcrypt($request->new_password);  // Menambahkan data password baru
            }
            $user->save();  // Menyimpan data user

            return redirect()->back()->with('success', 'Password berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Password lama salah.');
    }

    public function riwayatPeminjaman()   // Menampilkan riwayat peminjaman user
    {
        $peminjaman = $this->m_peminjaman->dataPeminjamanByUser(Auth::user()->id);
        $overdue = $this->m_peminjaman->pengembalianTerlambat(Auth::user()->id); // Mengambil data peminjaman yang telat pengembalian
        // dd($overdue);
        if ($overdue) {
            foreach ($peminjaman as $item) {
                foreach ($overdue as $late) {
                    if ($item->id == $late->id) { // Jika data peminjaman ada di data overdue
                        $item->status = 'Dikembalikan - Terlambat'; // Mengubah status menjadi Terlambat
                    }
                }
                // dd($item);
            }
        }


        return view('user.riwayatPeminjaman', compact('peminjaman'));
    }

    public function detailPeminjaman($id)   // Menampilkan detail peminjaman user
    {
        // $peminjaman = Peminjaman::find($id);    // Mengambil data peminjaman berdasarkan id
        // $detailPeminjaman = DetailPeminjaman::where('id_peminjaman', $id)->get();   // Mengambil data detail peminjaman berdasarkan id peminjaman
        // $barang = Inventaris::whereIn('id_barang', $detailPeminjaman->pluck('id_barang'))->get();   // Mengambil data barang berdasarkan id barang
        // $users = User::where('id', $peminjaman->id_user)->get()->first();

        // $peminjaman = $this->m_peminjaman->showPeminjaman($id);
        $peminjaman = Peminjaman::with(['detailPeminjaman.barang', 'user'])->findOrFail($id);
        // dd($peminjaman->detailPeminjaman);

        // dd($peminjaman);

        if (!$peminjaman) {
            return redirect()->back()
                ->with('error', 'Data peminjaman tidak ditemukan.');
        }

        return view('user.detailPeminjaman', compact('peminjaman'));
    }

    public function listTagihanKerusakan()
    {
        $modelTagihan = new TagihanKerusakan();
        // $data = TagihanKerusakan::with('laporan_kerusakan.detailPeminjaman.peminjaman')->get();
        // dd($data);
        $id = Auth::user()->id;
        $tagihan = $modelTagihan->getTagihanKerusakanByUserId($id);
        foreach ($tagihan as $item) { // Looping data tagihan
            $status = $this->getTransactionStatus($item->id); // Mengambil status transaksi berdasarkan id tagihan
            // dd($status);
            $item->status_tagihan = $status['transaction_status'] ?? 'unknown'; // Menambahkan status tagihan ke dalam data tagihan
        }
        // dd($tagihan);

        return view('user.tagihanKerusakan', compact('tagihan'));
    }

    public function barangTersedia()
    {
        $modelBarang = new Inventaris();
        $inventaris = $modelBarang->barangTersedia(); // Mengambil data barang yang tersedia

        // dd($inventaris);

        return view('user.ketersediaanBarang', compact('inventaris')); // Menampilkan data barang yang tersedia
    }

    public function detailBarang($id)
    {
        $modelBarang = new Inventaris();
        $inventaris = $modelBarang->getDataBarangUser($id); // Mengambil data barang berdasarkan id
        // dd( $inventaris);
        return view('user.detailBarang', compact('inventaris')); // Menampilkan data barang berdasarkan id
    }

    public function pengajuanBarang()
    {
        $pengajuan = $this->m_pengajuan->getPengajuanByUserId($this->authUser->id); // Mengambil data pengajuan peminjaman berdasarkan id user
        // dd($list);
        return view('user.pengajuanPeminjaman.index', compact('pengajuan')); // Menampilkan data pengajuan peminjaman
    }

    /**
     * Menampilkan detail pengajuan peminjaman berdasarkan ID pengajuan.
     *
     * @param int $id ID pengajuan peminjaman.
     * @return \Illuminate\View\View Mengembalikan tampilan detail pengajuan peminjaman.
     *
     * Proses:
     * - Mengambil data pengajuan peminjaman berdasarkan ID.
     * - Mengambil data detail pengajuan peminjaman berdasarkan ID pengajuan.
     * - Mengambil nama barang berdasarkan ID barang dari detail pengajuan.
     * - Menyusun data pengajuan dan detail barang ke dalam array.
     * - Menampilkan data ke dalam view 'user.pengajuanPeminjaman.showPengajuan'.
     */
    public function detailPengajuan($id)
    {
        $pengajuan = $this->m_pengajuan->getPengajuanById($id); // Mengambil data pengajuan peminjaman berdasarkan id
        $detail = $this->m_detail_pengajuan->getDetailPengajuanByPengajuanId($id); // Mengambil data detail pengajuan peminjaman berdasarkan id pengajuan
        // dd($detail);
        $barang = []; // Inisialisasi array untuk menyimpan data barang
        foreach ($detail as $item) { // Looping data detail pengajuan
            $barang[] = [
                'id_barang' => $item->id_barang,
                'nama_barang' => $this->m_barang->getNamaBarang($item->id_barang)->nama_barang, // Mengambil nama barang berdasarkan id barang
            ];
        }

        // dd($barang);

        $data = [
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'id_user' => $pengajuan->id_user,
            'tanggal_pengajuan' => $pengajuan->tanggal_pengajuan,
            'tanggal_mulai' => $pengajuan->tanggal_mulai,
            'tanggal_selesai' => $pengajuan->tanggal_selesai,
            'status_pengajuan' => $pengajuan->status_pengajuan,
            'alasan' => $pengajuan->alasan,
            'barang' => $barang,
            'nama_peminjam' => $this->authUser->name,
            'keterangan_pengajuan' => $pengajuan->keterangan_pengajuan,
            'surat_pengantar' => $pengajuan->surat_pengantar,
        ];

        // dd($data);

        return view('user.pengajuanPeminjaman.showPengajuan', compact('data')); // Menampilkan data detail pengajuan peminjaman
    }
    public function hasTagihan($id_user){
        $tagihan = $this->m_tagihan->userHasTagihan($id_user); // Mengambil data tagihan berdasarkan id user
        if ($tagihan) {
            return true; // Jika ada tagihan, kembalikan true
        }
        return false; // Jika tidak ada tagihan, kembalikan false
    }

    public function ajukanPeminjaman()
    {
        $hasTDenda = $this->hasTagihan($this->authUser->id); // Mengecek apakah user memiliki tagihan
        $hasTKerusakan = $this->m_tagihan_kerusakan->userHasTagihan($this->authUser->id); // Mengecek apakah user memiliki tagihan kerusakan

        if ($hasTDenda || $hasTKerusakan) { // Jika ada tagihan
            return redirect()->back()->with('error', 'Anda tidak dapat mengajukan peminjaman karena memiliki tagihan yang belum dibayar.'); // Jika ada tagihan, tampilkan pesan error
        }
        $barangTersedia = $this->m_barang->barangTersedia(); // Mengambil data barang yang tersedia
        // dd($barangTersedia);
        $listBarang = []; // Inisialisasi array untuk menyimpan data barang

        foreach ($barangTersedia as $item) { // Looping data barang yang tersedia
            if ($item->jenis_barang == 'Barang Pinjam') {
                 
                                $isBooked = $this->m_detail_pengajuan->isBooked($item->id_barang); // Mengecek apakah barang sudah dipesan
                                if (!$isBooked) { // Jika barang belum dipesan
                    $listBarang[] = [
                        'id_barang' => $item->id_barang,
                        'nama_barang' => $item->id_barang . ' - ' . $item->nama_barang,
                    ];
                }
            }
        }

        @json_encode($listBarang); // Mengubah data barang menjadi format JSON

        return view('user.pengajuanPeminjaman.createPengajuan', compact('listBarang')); // Menampilkan form peminjaman
    }
    
    public function emailNotifikasiPengajuan($id_pengajuan, $listBarang = [])
    {
        $pengajuan = $this->m_pengajuan->getPengajuanById($id_pengajuan); // Mengambil data pengajuan peminjaman berdasarkan id
        if (!$pengajuan) {
            return redirect()->back()
                ->with('error', 'Data pengajuan tidak ditemukan.');
        }

        $user = $pengajuan->user; // Mengambil data user dari pengajuan
        if (!$user) {
            return redirect()->back()
                ->with('error', 'Data user tidak ditemukan.');
        }

        $data = (object) [
            'nama_peminjam' => $user->name,
            'tanggal_pengajuan' => $pengajuan->tanggal_pengajuan,
            'tanggal_mulai' => $pengajuan->tanggal_mulai,
            'tanggal_selesai' => $pengajuan->tanggal_selesai,
            'status_pengajuan' => $pengajuan->status_pengajuan,
            'alasan' => $pengajuan->alasan,
            'surat_pengantar' => $pengajuan->surat_pengantar,
            'barang' => $listBarang, // Data barang yang diajukan
        ];

        $emailAdmin = $this->m_user->where('role', 'admin')->pluck('email')->toArray();
        
        // dd($data, $emailAdmin);

        try {
            Mail::to($emailAdmin)
                ->send(new NotifikasiPengajuan($data)); // Mengirim email notifikasi pengajuan
            if (app()->runningInConsole()) {
                if (Mail::failures()) {
                    echo "Gagal mengirim email notifikasi pengajuan.\n";
                } else {
                    echo "Email notifikasi pengajuan berhasil dikirim ke Admin Inventaris.\n";
                }
            }
            Log::info('Email notifikasi pengajuan berhasil dikirim ke Admin Inventaris.', [
                'id_pengajuan' => $id_pengajuan,
                'nama_peminjam' => $user->name,
                'email_admin' => $emailAdmin,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Email notifikasi pengajuan berhasil dikirim ke Admin Inventaris.',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email notifikasi pengajuan: ' . $e->getMessage());
        }
    }

    public function savePengajuan(Request $request)
    {
        // dd($request->all()); // Debugging untuk melihat data yang dikirim
        $user = $this->authUser; // Mengambil data user yang sedang login
        $barangDiajukan = json_decode($request->barang, true); // Mengubah data barang yang diajukan menjadi array
        
        if (!is_array($barangDiajukan)) {
            return redirect()->back()->withErrors(['barang' => 'Format barang tidak valid.']);
        }
        $request->merge(['barang' => $barangDiajukan]); // Pastikan barang adalah array sebelum validasi
        // dd($request->barang);
        $request->validate(
            [
                'barang' => 'required|array',
                'tgl_pinjam' => 'required|date',
                'tgl_tenggat' => 'required|date|after:tanggal_pinjam',
                'keterangan' => 'nullable|string|max:255',
            ],
            [
                'barang.required' => 'Barang harus dipilih.',
                'barang.array' => 'Barang harus berupa array.',
                'tgl_pinjam.required' => 'Tanggal pinjam harus diisi.',
                'tgl_pinjam.date' => 'Tanggal pinjam harus berupa tanggal yang valid.',
                'tgl_tenggat.required' => 'Tanggal kembali harus diisi.',
                'tgl_tenggat.date' => 'Tanggal kembali harus berupa tanggal yang valid.',
                'tgl_tenggat.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
                'keterangan.string' => 'Keterangan harus berupa string.',
                'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
            ]
        );

        // dd($request->barang);

        $pengajuanPeminjaman = $this->m_pengajuan; // Inisialisasi model pengajuan peminjaman
        $pengajuanPeminjaman->id_user = $user->id; // Mengambil id user yang sedang login
        $pengajuanPeminjaman->tanggal_pengajuan = now(); // Mengambil tanggal pengajuan
        $pengajuanPeminjaman->tanggal_mulai = $request->tgl_pinjam; // Mengambil tanggal mulai peminjaman
        $pengajuanPeminjaman->tanggal_selesai = $request->tgl_tenggat; // Mengambil tanggal selesai peminjaman
        $pengajuanPeminjaman->status_pengajuan = 'Pending'; // Mengatur status pengajuan
        $pengajuanPeminjaman->alasan = $request->keterangan; // Mengambil alasan peminjaman

        $file = $request->file('surat_pengantar'); // Mengambil file surat pengantar
        if ($file) { // Jika ada file surat pengantar
            $file->move('surat_pengantar', $file->getClientOriginalName()); // Pindahkan file ke folder surat_pengantar
            $pengajuanPeminjaman->surat_pengantar = $file->getClientOriginalName(); // Menyimpan nama file surat pengantar
        } else {
            $pengajuanPeminjaman->surat_pengantar = null; // Jika tidak ada file, set null
        }

        $pengajuanPeminjaman->save(); // Menyimpan data pengajuan peminjaman
        $dataBarang = []; // Inisialisasi array untuk menyimpan data barang yang diajukan

        foreach ($request->barang as $item) {
            $dataBarang[] = [
                'id_barang' => $item, // Mengambil id barang
                'nama_barang' => $this->m_barang->getBarangById($item)->nama_barang, // Mengambil nama barang berdasarkan id barang
            ];
            $this->m_detail_pengajuan->create([
                'id_pengajuan' => $pengajuanPeminjaman->id_pengajuan, // Mengambil id pengajuan peminjaman
                'id_barang' => $item, // Mengambil id barang
            ]);
        }

        // Mengirim email notifikasi pengajuan ke admin inventaris
        $this->emailNotifikasiPengajuan($pengajuanPeminjaman->id_pengajuan, $dataBarang); // Mengirim email notifikasi pengajuan


        return redirect()->route('user.pengajuanPeminjaman')->with('success', 'Pengajuan peminjaman berhasil diajukan.'); // Mengalihkan ke halaman pengajuan peminjaman dengan pesan sukses   
    }
    

    public function getTransactionStatus($orderId)
    {
        try {
            $response = Transaction::status($orderId);
            return (array) $response;
        } catch (Exception $e) {
            // Kalau error (misal order_id tidak ditemukan), return null atau custom response
            return [
                'transaction_status' => 'not_found',
                'error_message' => $e->getMessage(),
            ];
        }
    }

    public function tagihan()
    {
        $tagihan = $this->m_tagihan->getTagihanByUserId($this->authUser->id); // Mengambil data tagihan berdasarkan id user
        foreach ($tagihan as $item) { // Looping data tagihan
            $status = $this->getTransactionStatus($item->id); // Mengambil status transaksi berdasarkan id tagihan
            // dd($status);
            $item->status_tagihan = $status['transaction_status'] ?? 'unknown'; // Menambahkan status tagihan ke dalam data tagihan
        }
        // dd($tagihan);
        return view('user.tagihan.index', compact('tagihan')); // Menampilkan data tagihan
    }

    public function detailTagihan($id)
    {
        // dd($id);
        $tagihan = $this->m_tagihan->getDataTagihanById($id); // Mengambil data tagihan berdasarkan id

        $status = $this->getTransactionStatus($tagihan->id);

        // dd($status['transaction_status']);
        // Tambahkan key baru di tagihan
        $tagihan->status_tagihan = $status['transaction_status'] ?? 'unknown';

        
        // dd($tagihan);
        $barang = []; // Inisialisasi array untuk menyimpan data barang
        foreach ($tagihan->peminjaman->detailPeminjaman as $item) { // Looping data detail peminjaman
            $barang[] = [
                'id_barang' => $item->id_barang,
                'nama_barang' => $this->m_barang->getNamaBarang($item->id_barang)->nama_barang, // Mengambil nama barang berdasarkan id barang
            ];
        }
        // dd($barang);
        if (!$tagihan) {
            return redirect()->back()
                ->with('error', 'Data tagihan tidak ditemukan.');
        }
        $data = [
            'id' => $tagihan->id,
            'id_peminjaman' => $tagihan->id_peminjaman,
            'id_user' => $tagihan->peminjaman->id_user,
            'nama_peminjam' => $tagihan->peminjaman->user->name,
            'tgl_pinjam' => $tagihan->peminjaman->tgl_pinjam,
            'tgl_tenggat' => $tagihan->peminjaman->tgl_tenggat,
            'tgl_kembali' => $tagihan->peminjaman->tgl_kembali,
            'keterangan' => $tagihan->peminjaman->keterangan,
            'status' => $tagihan->peminjaman->status,
            'barang' => $barang,
            'id_tagihan' => $tagihan->id,
            'jumlah_tagihan' => $tagihan->jumlah_tagihan,
            'status_pembayaran' => $tagihan->status_pembayaran,
            'bukti_pembayaran' => $tagihan->bukti_pembayaran,
            'payment_url' => $tagihan->payment_url,
            'link_payment_created_at' => $tagihan->link_payment_created_at,
            'status_payment' => $tagihan->status_tagihan
        ];
        // dd($data);
        return view('user.tagihan.show', compact('data')); // Menampilkan data tagihan berdasarkan id
    }

    // Fungsi untuk membuat payment URL midtrans
    public function createPaymentUrlDenda($id)
    {
        $tagihan = $this->m_tagihan->getTagihanById($id); // Mengambil data tagihan berdasarkan id
        if (!$tagihan) {
            return redirect()->back()
                ->with('error', 'Data tagihan tidak ditemukan.');
        }
        if ($tagihan->payment_url != null || $tagihan->token != null) { 
            $status = $this->getTransactionStatus($tagihan->id);
            if ($status['transaction_status'] == 'expire' || $status['transaction_status'] == 'canceled') {
                $tagihan->id = (string) Str::uuid();
                $tagihan->payment_url = null;
                $tagihan->token = null;
                $tagihan->save;
            }
        }

        // Buat payment URL menggunakan Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $tagihan->id,
                'gross_amount' => abs($tagihan->jumlah_tagihan),
            ],
            'item_details' => [
                [
                    'id' => $tagihan->id,
                    'price' => abs($tagihan->jumlah_tagihan),
                    'quantity' => 1,
                    'name' => 'Tagihan Kerusakan',
                ],
            ],
            'customer_details' => [
                'first_name' => $this->authUser->name,
                'email' => $this->authUser->email,
            ],
        ];
        // dd($params);

        

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Simpan payment URL ke dalam database
        $tagihan->token = $snapToken; // Simpan payment URL ke dalam kolom payment_url

        $tagihan->payment_url = \Midtrans\Snap::createTransaction($params)->redirect_url; // Simpan payment URL ke dalam kolom payment_url
        // dd($tagihan->payment_url, $tagihan->token);
        $tagihan->link_payment_created_at = now(); // Simpan waktu pembuatan payment URL
        $tagihan->status_pembayaran = 'pending';
        $tagihan->save();

        return redirect()->route('user.tagihan')->with('success', 'Payment URL berhasil dibuat.');
    }

        public function createPaymentUrlKerusakan($id)
    {
        $tagihan = TagihanKerusakan::find($id); // Mengambil data tagihan berdasarkan id
        if (!$tagihan) {
            return redirect()->back()
                ->with('error', 'Data tagihan tidak ditemukan.');
        }

        if ($tagihan->payment_url != null || $tagihan->token != null) { 
            $status = $this->getTransactionStatus($tagihan->id);
            if ($status['transaction_status'] == 'expire' || $status['transaction_status'] == 'canceled') {
                $tagihan->id = (string) Str::uuid();
                $tagihan->payment_url = null;
                $tagihan->token = null;
                $tagihan->save;
            }
        }

        // Buat payment URL menggunakan Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $tagihan->id,
                'gross_amount' => abs($tagihan->total_tagihan),
            ],
            'item_details' => [
                [
                    'id' => $tagihan->id,
                    'price' => abs($tagihan->total_tagihan),
                    'quantity' => 1,
                    'name' => 'Tagihan Kerusakan',
                ],
            ],
            'customer_details' => [
                'first_name' => $this->authUser->name,
                'email' => $this->authUser->email,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Simpan payment URL ke dalam database
        $tagihan->token = $snapToken; // Simpan payment URL ke dalam kolom payment_url

        $tagihan->payment_url = \Midtrans\Snap::createTransaction($params)->redirect_url; // Simpan payment URL ke dalam kolom payment_url
        // dd($tagihan->payment_url, $tagihan->token);
        $tagihan->save();

        return redirect()->back()->with('success', 'Payment URL berhasil dibuat.');
    }

    public function webhooks(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            if (!$orderId) {
                return response()->json(['error' => 'Missing order_id'], 400);
            }
            
            $serverKey = env('MIDTRANS_SERVER_KEY'); // Pastikan ini diisi di .env (yang PRODUCTION)
            $auth = base64_encode($serverKey);
    
            // Panggil Midtrans API untuk dapatkan status transaksi
            $midtransResponse = Http::withHeaders([
                'Authorization' => "Basic $auth",
                'Accept' => 'application/json',
            ])->get("https://api.midtrans.com/v2/{$orderId}/status");
    
            // Pastikan respons OK
            if (!$midtransResponse->ok()) {
                Log::error('Midtrans API error', [
                    'status' => $midtransResponse->status(),
                    'body' => $midtransResponse->body(),
                ]);
                return response()->json(['error' => 'Failed to fetch status from Midtrans'], 500);
            }
    
            $response = $midtransResponse->object(); // Bisa juga pakai ->json() jika prefer array
    
            $transactionStatus = $response->transaction_status ?? null;
            $transactionId = $response->transaction_id ?? null;
            
            if (!$transactionStatus || !$transactionId) {
                Log::error('Invalid response structure from Midtrans', [
                    'raw_response' => $midtransResponse->body(),
                    'parsed_response' => $response,
                    'missing_transaction_status' => !$transactionStatus,
                    'missing_transaction_id' => !$transactionId,
                ]);
                return response()->json([
                    'error' => 'Invalid response from Midtrans',
                    'details' => [
                        'missing_transaction_status' => !$transactionStatus,
                        'missing_transaction_id' => !$transactionId,
                    ]
                ], 500);
            }
    
            // Coba cari ke tabel denda dulu
            $denda = $this->m_tagihan->getTagihanById($orderId);
            $kerusakan = TagihanKerusakan::where('id', $orderId)->first();
    
            if ($denda) {
                if (in_array($denda->status_pembayaran, ['settlement', 'capture'])) {
                    return response()->json('Payment has been already processed');
                }
    
                $denda->status_pembayaran = $transactionStatus;
                $denda->save();
            } elseif ($kerusakan) {
                if (in_array($kerusakan->status, ['settlement', 'capture'])) {
                    return response()->json('Payment has been already processed');
                }
    
                $kerusakan->status = $transactionStatus;
                $kerusakan->save();
            } else {
                return response()->json('Order ID not found');
            }
    
            return response()->json(['status' => 'success']);
    
        } catch (\Throwable $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
