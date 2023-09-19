<?php

use App\Http\Controllers\UI\StoreListController;
use App\Http\Controllers\UI\VendingMachineController;
use App\Http\Controllers\UI\MachineSlotController;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'store'], function(){
        Route::get('/', [App\Http\Controllers\UI\StoreListController::class, 'index'])->name('store.index');
        Route::post('/create/', [App\Http\Controllers\UI\StoreListController::class, 'store'])->name('store.create');
        Route::get('/{id}', [App\Http\Controllers\UI\StoreListController::class, 'show'])->name('store.show');
        Route::post('/update', [App\Http\Controllers\UI\StoreListController::class, 'update'])->name('store.update');

        Route::group(['prefix' => 'machine'], function(){
            Route::get('/{id}', [App\Http\Controllers\UI\VendingMachineController::class, 'index'])->name('machine.index');
            Route::post('/create/{id}', [App\Http\Controllers\UI\VendingMachineController::class, 'store'])->name('machine.create');
            Route::post('/update/{id}', [App\Http\Controllers\UI\VendingMachineController::class, 'update'])->name('machine.update');
            Route::post('/delete/', [App\Http\Controllers\UI\VendingMachineController::class, 'delete'])->name('machine.delete');

            Route::group(['prefix' => 'machine-slots'], function(){
                Route::get('/{machineSlotId}', [App\Http\Controllers\UI\MachineSlotController::class, 'index'])->name('machine-slots.index');
                Route::post('/create/{id}', [App\Http\Controllers\UI\MachineSlotController::class, 'store'])->name('machine-slots.create');
                Route::get('/update/{id}', [App\Http\Controllers\UI\MachineSlotController::class, 'update'])->name('machine-slots.update');
                Route::get('/updateSpare/{id}', [App\Http\Controllers\UI\MachineSlotController::class, 'updateSparePart'])->name('machine-slots.updateSpare');
                Route::post('/delete/', [App\Http\Controllers\UI\MachineSlotController::class, 'delete'])->name('machine-slots.delete');
            });
        });

    });

    Route::group(['middleware' => ['user-role:super admin|admin']], function(){
        Route::group(['prefix' => 'users'], function(){
            Route::get('/', [App\Http\Controllers\UI\UserManagementController::class, 'index'])->name('usermanagement.index');
            Route::post('/create', [App\Http\Controllers\UI\UserManagementController::class, 'create'])->name('usermanagement.create');
            Route::post('/update', [App\Http\Controllers\UI\UserManagementController::class, 'update'])->name('usermanagement.update');
            Route::get('/delete', [App\Http\Controllers\UI\UserManagementController::class, 'delete'])->name('usermanagement.delete');
        });

        Route::group(['prefix' => 'products'], function(){
            Route::get('/', [App\Http\Controllers\UI\ProductController::class, 'index'])->name('products.index');
            Route::post('/create/', [App\Http\Controllers\UI\ProductController::class, 'create'])->name('products.create');
            Route::post('/update/{id}', [App\Http\Controllers\UI\ProductController::class, 'update'])->name('products.update');
            Route::post('/AjaxUpdate/{id}', [App\Http\Controllers\UI\ProductController::class, 'updateAjax'])->name('products.ajaxUpdate');
            Route::post('/delete/{id}', [App\Http\Controllers\UI\ProductController::class, 'softDelete'])->name('products.delete');
        });

        Route::get('/idle-video', [App\Http\Controllers\UI\IdleVideoController::class, 'index'])->name('idle-video.index');
        Route::post('/idle-video/upload', [App\Http\Controllers\UI\IdleVideoController::class, 'upload'])->name('idle-video.upload');
        Route::get('/idle-video/publish', [App\Http\Controllers\UI\IdleVideoController::class, 'publish'])->name('idle-video.publish');

        Route::get('/audit', [App\Http\Controllers\UI\AuditController::class, 'index'])->name('audit.index');
    });

    Route::group(['middleware' => ['user-role:super admin']], function(){
        Route::get('/idle-video', [App\Http\Controllers\UI\IdleVideoController::class, 'index'])->name('idle-video.index');
        Route::post('/idle-video/upload', [App\Http\Controllers\UI\IdleVideoController::class, 'upload'])->name('idle-video.upload');
        Route::get('/idle-video/publish', [App\Http\Controllers\UI\IdleVideoController::class, 'publish'])->name('idle-video.publish');
    });

    Route::group(['middleware' => ['user-role:super admin']], function(){
        Route::group(['prefix' => 'categories'], function(){
            Route::get('/', [App\Http\Controllers\UI\categoryController::class, 'index'])->name('category.index');
            Route::post('/create', [App\Http\Controllers\UI\categoryController::class, 'store'])->name('category.create');
            Route::post('/update', [App\Http\Controllers\UI\categoryController::class, 'update'])->name('category.update');
            Route::get('/delete', [App\Http\Controllers\UI\categoryController::class, 'delete'])->name('category.delete');
        });
    });

    Route::group(['middleware' => ['user-role:super admin|admin|rm|arm']], function(){
        Route::group(['prefix' => 'transactions'], function(){
            Route::get('/', [App\Http\Controllers\UI\TransactionController::class, 'index'])->name('transactions.index');
        });

        Route::group(['prefix' => 'reports'], function(){
            Route::get('/', [App\Http\Controllers\UI\ReportController::class, 'index'])->name('reports.index');
            Route::get('/export/', [App\Http\Controllers\UI\ReportController::class, 'export'])->name('reports.export');
        });

        Route::group(['prefix' => 'analytics'], function(){
            Route::get('/', [App\Http\Controllers\UI\AnalyticsController::class, 'index'])->name('analytics.index');
            Route::get('/export/', [App\Http\Controllers\UI\AnalyticsController::class, 'export'])->name('analytics.export');
        });
    });

});

Route::get('view-status/{requestId}', [App\Http\Controllers\Api\v1\ResponsesController::class, 'index']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'loginOverrided'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login.index');

Route::get('/machine-dashboard', [App\Http\Controllers\UI\MachineDashBoardController::class, 'index'])->name('machine-dashboard');

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
