<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', [UserController::class, 'get']);
});

Route::prefix('payments')->middleware('auth:sanctum')->group(function () {
    Route::get('/{id}', [PaymentController::class, 'index']);
    Route::post('/', [PaymentController::class, 'store']);
});