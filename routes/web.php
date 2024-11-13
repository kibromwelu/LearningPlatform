<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Models\Letter;

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
Route::get('/cv', function () {
    return view('cv');
});
Route::get('/pdf', function () {
    $response = Letter::get();
    $imagePath = 'http://192.168.1.4:8000/api/auth/post-file/1728349660_Screenshot%20(9).png';
    $img = 'data:image/jpeg;base64,' . base64_encode(
        storage_path('/app/public/AlNU3WTK_400x400.jpg')
    );
    return view('letter', ['name' => 'Kibrom', 'image_path' => $img, 'data' => $response]);
});
