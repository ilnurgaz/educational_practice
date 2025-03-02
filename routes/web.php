<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChartController;

Route::get('/sales-data', [ChartController::class, 'getSalesData']);





Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('parts', PartController::class);
    Route::get('/parts/{id}/edit', [PartController::class, 'edit'])->name('parts.edit');
    Route::put('/parts/{id}', [PartController::class, 'update'])->name('parts.update');
    Route::get('/parts/{part}', [PartController::class, 'show'])->name('parts.show');

    Route::resource('suppliers', SupplierController::class);
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::post('/suppliers/{supplier}/parts', [SupplierController::class, 'storePart'])->name('suppliers.storePart');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/select-supplier', [PurchaseController::class, 'selectSupplier'])->name('purchases.selectSupplier');
    Route::post('/purchases/choose-supplier', [PurchaseController::class, 'chooseSupplier'])->name('purchases.chooseSupplier');
    Route::get('/purchases/create/{supplier}', [PurchaseController::class, 'createPurchase'])->name('purchases.createWithSupplier');
    Route::post('/purchases/store', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{id}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::post('/purchases/{id}/update', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::put('/purchases/{purchase}/update', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::get('/purchases/create/{supplier_id}', [PurchaseController::class, 'createPurchase'])
    ->name('purchases.createWithSupplier');



    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

});

require __DIR__.'/auth.php';