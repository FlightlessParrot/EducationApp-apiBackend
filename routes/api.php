<?php

use App\Events\PaymentStatusChange;
use App\Http\Controllers\PaymentController;
use App\Models\Payment;
use Illuminate\Http\Request;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();

});
Route::post('/payment/update',[PaymentController::class,'updatePayment']);
Route::get('/payment/update/{payment}',function(Payment $payment){$payment->status='CONFIRMED'; $payment->save(); PaymentStatusChange::dispatch($payment);});
