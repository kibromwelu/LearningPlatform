<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Route::get('products', [ProductController::class, 'index']);

    // // Route::get('products/create', [ProductController::class, 'create']);
    // Route::post('products', [ProductController::class, 'store']);
    // // Route::get('products/{id}/edit', [ProductController::class, 'edit']);
    // Route::put('products/{id}', [ProductController::class, 'update']);
    // Route::delete('products/{id}', [ProductController::class, 'destroy']);
    // Route::get('/dashboard', [AuthController::class, 'login'])->name('dashboard');
    // Route::get('/products', [ProductController::class, 'index'])->name('index');
Route::get('/', function () {
    return view('welcome');
});