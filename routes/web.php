<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PartController;




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    Route::get('/', [SupplierController::class, 'index']);

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

});


require __DIR__.'/auth.php';
