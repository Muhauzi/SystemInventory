<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelolaUsersController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LaporanKerusakanController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PengajuanController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/tagihan/denda', [AdminController::class, 'tagihanDenda'])->name('tagihan.denda');
});

Route::prefix('kelola_user')->name('kelola_user.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', [KelolaUsersController::class, 'index'])->name('index');
    Route::get('/add', [KelolaUsersController::class, 'create'])->name('add');
    Route::post('/store', [KelolaUsersController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [KelolaUsersController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [KelolaUsersController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [KelolaUsersController::class, 'destroy'])->name('delete');
});

Route::prefix('inventaris')->name('inventaris.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', [InventarisController::class, 'index'])->name('index');
    Route::get('/list/{id}', [InventarisController::class, 'barangByKategori'])->name('list');
    Route::get('/show/{id}', [InventarisController::class, 'show'])->name('show');
    Route::get('/add', [InventarisController::class, 'create'])->name('create');
    Route::post('/store', [InventarisController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [InventarisController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [InventarisController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [InventarisController::class, 'destroy'])->name('delete');
    Route::get('/qr/{id}', [InventarisController::class, 'qrCreate'])->name('qr');
    Route::post('ScanQR', [InventarisController::class, 'scanQR'])->name('scanQR');
})->middleware(['auth', 'verified', 'admin']);

Route::prefix('peminjaman')->name('peminjaman.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', [PeminjamanController::class, 'index'])->name('index');
    Route::get('/listPerizinan', [PeminjamanController::class, 'listPerizinan'])->name('listPerizinan');
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('pengembalian');
    Route::get('/show/{id}', [PeminjamanController::class, 'show'])->name('show');
    Route::get('/add', [PeminjamanController::class, 'create'])->name('add');
    Route::post('/store', [PeminjamanController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [PeminjamanController::class, 'edit'])->name('edit');
    Route::post('/return_peminjaman', [PeminjamanController::class, 'return'])->name('manualReturn');
    Route::post('/update/{id}', [PeminjamanController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [PeminjamanController::class, 'destroy'])->name('delete');
    Route::get('/buktiPinjam/{id}', [PeminjamanController::class, 'buktiPinjam'])->name('buktiPinjam');
    Route::get('/buktiKembali/{id}', [PeminjamanController::class, 'buktiKembali'])->name('buktiKembali');
    Route::post('/scanReturn', [PeminjamanController::class, 'return'])->name('scanReturn');
    Route::get('/laporan', [PeminjamanController::class, 'unduhLaporan'])->name('laporan');
    Route::get('/request_unduh', [PeminjamanController::class, 'unduhLaporan'])->name('request_unduh');
    Route::post('/download_laporan', [PeminjamanController::class, 'unduhLaporanPeminjaman'])->name('download_laporan');
    Route::post('/updateBatasPeminjaman/{id}', [PeminjamanController::class, 'updateBatasPeminjaman'])->name('updateBatasPeminjaman');
    
});

Route::prefix('pengajuan')->name('pengajuan.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [PengajuanController::class, 'index'])->name('index');
    Route::get('/show/{id}', [PengajuanController::class, 'show'])->name('show');
    Route::post('/show/{id}/update_status', [PengajuanController::class, 'updateStatusPengajuan'])->name('updateStatus');
    Route::post('/show/{id}/proses_pengajuan', [PengajuanController::class, 'pengajuanDiambil'])->name('pengajuanDiambil');
});

Route::prefix('kerusakan')->name('laporan_kerusakan.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [LaporanKerusakanController::class, 'index'])->name('index');
    Route::get('/show/{id}', [LaporanKerusakanController::class, 'detailKerusakan'])->name('show');
    Route::get('/add/{id}', [LaporanKerusakanController::class, 'formSubmitKerusakan'])->name('add');
    Route::post('/store', [LaporanKerusakanController::class, 'submitKerusakan'])->name('store');
    Route::get('/edit/{id}', [LaporanKerusakanController::class, 'editKerusakan'])->name('edit');
    Route::post('/update/{id}', [LaporanKerusakanController::class, 'updateKerusakan'])->name('update');
    Route::delete('/delete/{id}', [LaporanKerusakanController::class, 'destroyKerusakan'])->name('delete');
    Route::post('/storeTagihan', [LaporanKerusakanController::class, 'storeTagihan'])->name('storeTagihan');
    Route::get('/request_unduh', [LaporanKerusakanController::class, 'unduhLaporan'])->name('request_unduh');
    Route::post('/download_laporan', [LaporanKerusakanController::class, 'downloadLaporanKerusakan'])->name('download_laporan');
});

Route::prefix('kategori')->name('kategori.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', [KategoriController::class, 'index'])->name('index');
    Route::get('/add', [KategoriController::class, 'create'])->name('add');
    Route::post('/store', [KategoriController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [KategoriController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [KategoriController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [KategoriController::class, 'destroy'])->name('delete');
});

Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    Route::get('/profile', [UsersController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [UsersController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [UsersController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile/delete', [UsersController::class, 'destroy'])->name('profile.destroy');
    Route::post(('profile/change-password'), [UsersController::class, 'changePassword'])->name('profile.change-password');
    Route::get('/riwayat_peminjaman', [UsersController::class, 'riwayatPeminjaman'])->name('riwayat_peminjaman');
    Route::get('/riwayat_peminjaman/{id}', [UsersController::class, 'detailPeminjaman'])->name('detail_peminjaman');
    Route::get('/buktiPinjam/{id}', [PeminjamanController::class, 'buktiPinjam'])->name('buktiPinjam');
    Route::get('/tagihan_kerusakan', [UsersController::class, 'listTagihanKerusakan'])->name('TagihanKerusakan');
    Route::get('/tagihan_kerusakan/{id}', [LaporanKerusakanController::class, 'detailKerusakan'])->name('show.TagihanKerusakan');
    Route::get('/barang_tersedia', [UsersController::class, 'barangTersedia'])->name('barangTersedia');
    Route::get('/barang_tersedia/{id}', [InventarisController::class, 'show'])->name('detailBarang');
    Route::get('/ajukan_peminjaman', [UsersController::class, 'ajukanPeminjaman'])->name('addPengajuan');
    Route::post('/save_pengajuan', [UsersController::class, 'savePengajuan'])->name('savePengajuan');
    Route::get('/pengajuan_peminjaman', [UsersController::class, 'pengajuanBarang'])->name('pengajuanPeminjaman');
    Route::get('/pengajuan_peminjaman/{id}', [UsersController::class, 'detailPengajuan'])->name('showPengajuan');
    Route::delete('/pengajuan_peminjaman/{id}/delete', [PengajuanController::class, 'destroy'])->name('deletePengajuan');
    Route::get('/tagihan', [UsersController::class, 'tagihan'])->name('tagihan');
    Route::get('/tagihan_denda/{id}', [UsersController::class, 'detailTagihan'])->name('tagihan.show');
    Route::post('/tagihan_denda/{id}/bayar', [UsersController::class, 'createPaymentUrlDenda'])->name('tagihan.bayar');
    Route::post('/tagihan_kerusakan/{id}/bayar', [UsersController::class, 'createPaymentUrlDenda'])->name('kerusakan.bayar');
    Route::post('/tagihan_kerusakan/{id}/bayar', [UsersController::class, 'createPaymentUrlKerusakan'])->name('tagihan_kerusakan.bayar'); 
    Route::post('/tagihan/upload_bukti/{id}', [UsersController::class, 'uploadBuktiPembayaran'])->name('tagihan.upload_bukti');
});

Route::prefix('pimpinan')->name('pimpinan.')->middleware(['auth', 'verified', 'pimpinan'])->group(function () {
    Route::get('/monitor_barang', [InventarisController::class, 'index'])->name('monitor.barang');
    Route::get('/monitor_barang/list/{id}', [InventarisController::class, 'barangByKategori'])->name('monitor.listBarang');
    Route::get('/monitor_barang/show/{id}', [InventarisController::class, 'show'])->name('monitor.showBarang');
    Route::get('/list_pegawai', [AtasanController::class, 'listPegawai'])->name('list_pegawai');
    Route::get('/detail_pegawai/{id}', [AtasanController::class, 'detailPegawai'])->name('detail_pegawai');
    Route::get('/izin_peminjaman', [AtasanController::class, 'izinPeminjamanInventaris'])->name('izin_peminjaman');
    Route::get('/izin_peminjaman/{id}', [AtasanController::class, 'detailIzinPeminjamanInventaris'])->name('detail_izin');
    Route::post('/update_izin/{id}', [AtasanController::class, 'updateIzinPeminjamanInventaris'])->name('update_izin');
    Route::get('/download_laporan_kerusakan', [AtasanController::class, 'downloadLaporanKerusakan'])->name('download_laporan_kerusakan');
    Route::get('/laporan_transaksi', [PeminjamanController::class, 'laporan'])->name('laporan_transaksi');
    Route::get('/request_unduh_kerusakan', [LaporanKerusakanController::class, 'unduhLaporan'])->name('request_unduh_kerusakan');
    Route::get('/request_unduh_transaksi', [PeminjamanController::class, 'unduhLaporan'])->name('request_unduh_transaksi');
    Route::post('/download_laporan_transaksi', [PeminjamanController::class, 'unduhLaporanPeminjaman'])->name('download_laporan_transaksi');
    Route::get('/laporan_kerusakan', [LaporanKerusakanController::class, 'index'])->name('laporan_kerusakan');

});

Route::get('/request_unduh_laporan_kerusakan', [LaporanKerusakanController::class, 'unduhLaporan'])->name('request_unduh_laporan_kerusakan')->middleware('auth', 'verified');
Route::get('/request_unduh_laporan_transaksi', [PeminjamanController::class, 'unduhLaporan'])->name('request_unduh_laporan_transaksi')->middleware('auth', 'verified');

// test route
Route::get('/test/{id}', [PengajuanController::class, 'pengajuanDiambil'])->name('test');

Route::prefix('download')->name('download.')->middleware(['auth', 'verified'])->group(function(){
    Route::post('/laporan_transaksi', [PeminjamanController::class, 'unduhLaporanPeminjaman'])->name('laporan_transaksi');
    Route::post('/laporan_kerusakan', [LaporanKerusakanController::class, 'downloadLaporanKerusakan'])->name('laporan_kerusakan');
});
// Route::get('/sendTagihan/{id}', [LaporanKerusakanController::class, 'sendEmailPenagihan'])->name('sendTagihan');
Route::get('/getLaporanKerusakan', [LaporanKerusakanController::class, 'getLaporanKerusakan'])->name('getLaporanKerusakan');

Route::middleware('auth')->group(function () {
    Route::get('/unauthorized', function () {
        return view('errors.unauthorized');
    })->name('unauthorized');
});

require __DIR__ . '/auth.php';
