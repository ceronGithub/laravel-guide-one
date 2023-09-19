<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\MachineController;
use App\Http\Controllers\Api\v1\TransactionController;
use App\Http\Controllers\Api\v1\ResponsesController;
use App\Http\Controllers\Api\v1\ProcessorTransactionsController;
use App\Http\Controllers\Api\v1\AuthenticationController;
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

Route::namespace('api.v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register.api');

    Route::post('/check', [MachineController::class, 'checkMachineID'])->name('machine.check.api');

    Route::post('/machine/slot/reset', [MachineController::class, 'resetSlot'])->name('machine.slot.reset.api');
    Route::group(['middleware' => ['auth:api']], function () {
	Route::get('/video/idle', [MachineController::class, 'getVideoIdle'])->name('video.idle.api');

    	Route::get('/machine/slot', [MachineController::class, 'getMachineSlotList'])->name('machine.get.api');
    	Route::post('/machine/status', [MachineController::class, 'sendMachineStatus'])->name('machine.status.api');
    	Route::post('/machine/printerstatus', [MachineController::class, 'sendPrinterStatus'])->name('machine.printer.status.api');
        Route::post('/machine/register', [MachineController::class, 'registerMachineID'])->name('machine.register.api');
        Route::post('/machine/slot/register', [MachineController::class, 'registerMachineSlot'])->name('machine.register.api');

 	Route::post('/transaction/create', [TransactionController::class, 'createPurchaseOrder'])->name('transaction.create.api');
    	Route::post('/transaction/payment', [TransactionController::class, 'createPayment'])->name('payment.create.api');
    	Route::post('/transaction/details', [TransactionController::class, 'getPurchaseOrderDetails'])->name('transaction.get.api');

        Route::post('check-transaction', [ResponsesController::class, 'show']);
        Route::post('request-for-payment-url', [ProcessorTransactionsController::class, 'store']);
        Route::post('cancel-transaction', [ProcessorTransactionsController::class, 'update']);
        Route::post('query-transaction', [ProcessorTransactionsController::class, 'show']);
    });

    Route::group(['middleware' => ['client:default']], function () {
        Route::get('/video/idle', [MachineController::class, 'getVideoIdle'])->name('video.idle.api');

    	Route::get('/machine/slot', [MachineController::class, 'getMachineSlotList'])->name('machine.get.api');
    	Route::post('/machine/status', [MachineController::class, 'sendMachineStatus'])->name('machine.status.api');
    	Route::post('/machine/printerstatus', [MachineController::class, 'sendPrinterStatus'])->name('machine.printer.status.api');
        Route::post('/machine/register', [MachineController::class, 'registerMachineID'])->name('machine.register.api');
        Route::post('/machine/slot/register', [MachineController::class, 'registerMachineSlot'])->name('machine.register.api');

 	    Route::post('/transaction/create', [TransactionController::class, 'createPurchaseOrder'])->name('transaction.create.api');
    	Route::post('/transaction/payment', [TransactionController::class, 'createPayment'])->name('payment.create.api');
    	Route::post('/transaction/details', [TransactionController::class, 'getPurchaseOrderDetails'])->name('transaction.get.api');


        Route::post('check-transaction', [ResponsesController::class, 'show']);
        Route::post('request-for-payment-url', [ProcessorTransactionsController::class, 'store']);
        Route::post('cancel-transaction', [ProcessorTransactionsController::class, 'update']);
        Route::post('query-transaction', [ProcessorTransactionsController::class, 'show']);
    });

    Route::post('notifications', [ResponsesController::class, 'store']);
    Route::post('oauth/token', [AuthenticationController::class, 'requestToken']);
});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
