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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
    Route::post('/update/{id}', [PeminjamanController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [PeminjamanController::class, 'destroy'])->name('delete');
    Route::get('/buktiPinjam/{id}', [PeminjamanController::class, 'buktiPinjam'])->name('buktiPinjam');
    Route::get('/buktiKembali/{id}', [PeminjamanController::class, 'buktiKembali'])->name('buktiKembali');
    Route::post('/scanReturn', [PeminjamanController::class, 'scanReturn'])->name('scanReturn');
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
});

Route::prefix('kategori')->name('kategori.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', [KategoriController::class, 'index'])->name('index');
    Route::get('/add', [KategoriController::class, 'create'])->name('add');
    Route::post('/store', [KategoriController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [KategoriController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [KategoriController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [KategoriController::class, 'destroy'])->name('delete');
});

Route::prefix('user')->name('user.')->middleware(['auth', 'verified',])->group(function () {
    Route::get('/profile', [UsersController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [UsersController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [UsersController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile/delete', [UsersController::class, 'destroy'])->name('profile.destroy');
    Route::post(('profile/change-password'), [UsersController::class, 'changePassword'])->name('profile.change-password');
    Route::get('/riwayat_peminjaman', [UsersController::class, 'riwayatPeminjaman'])->name('riwayat_peminjaman');
    Route::get('/riwayat_peminjaman/{id}', [UsersController::class, 'detailPeminjaman'])->name('detail_peminjaman');
    Route::get('/buktiPinjam/{id}', [PeminjamanController::class, 'buktiPinjam'])->name('buktiPinjam');
});

Route::prefix('pimpinan')->name('pimpinan.')->middleware(['auth', 'verified', 'pimpinan'])->group(function () {
    Route::get('/list_pegawai', [AtasanController::class, 'listPegawai'])->name('list_pegawai');
    Route::get('/detail_pegawai/{id}', [AtasanController::class, 'detailPegawai'])->name('detail_pegawai');
    Route::get('/izin_peminjaman', [AtasanController::class, 'izinPeminjamanInventaris'])->name('izin_peminjaman');
    Route::get('/izin_peminjaman/{id}', [AtasanController::class, 'detailIzinPeminjamanInventaris'])->name('detail_izin');
    Route::post('/update_izin/{id}', [AtasanController::class, 'updateIzinPeminjamanInventaris'])->name('update_izin');
    Route::get('/download_laporan_kerusakan', [AtasanController::class, 'downloadLaporanKerusakan'])->name('download_laporan_kerusakan');
});

Route::middleware('auth')->group(function () {
    Route::get('/unauthorized', function () {
        return view('errors.unauthorized');
    })->name('unauthorized');
});

require __DIR__ . '/auth.php';
