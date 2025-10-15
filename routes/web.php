<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItensController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
use App\Models\Item;
use App\Models\Request as ItemRequest;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    
    Route::controller(DashboardController::class)->prefix('/')->group(function () {
        Route::get('/', 'index')->name('dashboard');
    });

    Route::controller(ReportController::class)->prefix('/rel')->group(function () {
        Route::post('/', 'export')->name('report.export')->can('viewRequests', ItemRequest::class);
    });

    Route::controller(RequestController::class)->prefix('/r')->group(function () {
        Route::get('/', 'index')->name('request.index')->can('viewRequests', ItemRequest::class);
        Route::get('/export', 'export')->name('request.export')->can('viewRequests', ItemRequest::class);
        Route::get('/s', 'indexSolicitar')->name('request.solicitar');
        Route::post("/s/{item}", 'store')->name('request.store');
        Route::post('/{req}/avaliar', 'avaliar')->name('request.avaliar')->can('avaliarRequests', ItemRequest::class);
    });
    
    Route::controller(ItensController::class)->prefix('/i')->group(function () {
        Route::get('/', 'index')->name('itens.index')->can('viewItems', Item::class);
        Route::get('/export', 'export')->name('itens.export')->can('viewItems', Item::class);
        Route::post('/', 'create')->name('itens.create')->can('storeItems', Item::class);
        Route::put('/{item}', [ItensController::class, 'edit'])->name('itens.update')->can('storeItems', Item::class);
        Route::delete('/{item}', [ItensController::class, 'destroy'])->name('itens.destroy')->can('storeItems', Item::class);
    });

    Route::controller(UserController::class)->prefix('/u')->group(function () {
        Route::get('/', 'index')->name('users.index')->can('crud', User::class);
        Route::post('/', 'store')->name('users.create')->can('crud', User::class);
        Route::post('/{user}/edit', action: 'edit')->name('users.edit')->can('crud', User::class);
        Route::get('/{user}/del', action: 'destroy')->name('users.delete')->can('crud', User::class);
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
