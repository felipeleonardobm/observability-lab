<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('payments')->group(function () {
    Route::get('/{id}', [PaymentController::class, 'index']);
    Route::post('/', [PaymentController::class, 'store']);
});