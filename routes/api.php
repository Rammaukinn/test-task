<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('payment/first-payment-gateway', [PaymentController::class, 'firstPaymentGateway']);
Route::post('payment/second-payment-gateway', [PaymentController::class, 'secondPaymentGateway']);
