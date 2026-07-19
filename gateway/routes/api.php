<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Telemetry\Telemetry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', [UserController::class, 'get']);
});

Route::prefix('payments')->middleware('auth:sanctum')->group(function () {
    Route::get('/{id}', [PaymentController::class, 'index']);
    Route::post('/', [PaymentController::class, 'store']);
});

Route::get('/otel-test', function (Telemetry $telemetry) {

    return $telemetry->span(
        'Test Span',
        fn () => response()->json([
            'ok' => true,
        ])
    );
});