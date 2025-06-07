<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanKerusakanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UsersController;


// Route::post('webhooks', [LaporanKerusakanController::class, 'webhooks']);
Route::post('webhooks_tagihan', [UsersController::class, 'webhooks']);
Route::get('laporan-kerusakan', [LaporanKerusakanController::class, 'getLaporanKerusakanFilter'])->name('api.laporan_kerusakan');
Route::get('laporan-transaksi', [PeminjamanController::class, 'getLaporanTransaksiFilter'])->name('api.laporan_transaksi');



// check api route
Route::get('check', function () {
    return response()->json(['message' => 'success']);
});
