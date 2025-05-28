<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanKerusakanController; 
use App\Http\Controllers\UsersController;


// Route::post('webhooks', [LaporanKerusakanController::class, 'webhooks']);
Route::post('webhooks_tagihan', [UsersController::class, 'webhooks']);


// check api route
Route::get('check', function () {
    return response()->json(['message' => 'success']);
});
