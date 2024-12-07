<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanKerusakanController; 


Route::post('webhooks', [LaporanKerusakanController::class, 'webhooks']);

// check api route
Route::get('check', function () {
    return response()->json(['message' => 'success']);
});
