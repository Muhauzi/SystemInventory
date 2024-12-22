<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/kelola_user', [AdminController::class, 'kelola_user'])->name('kelola_user');
    Route::get('/kelola_inventaris', [AdminController::class, 'kelola_inventaris'])->name('kelola_inventaris');
    Route::get('/kelola_user/add', [AdminController::class, 'create'])->name('user.create');
    Route::post('/kelola_user/store', [AdminController::class, 'store'])->name('user.store');
});