<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/admin', function () {
//     if (Gate::allows('admin')) {
//         return view('dashboard');
//     }

//     abort(403, 'Unauthorized action.');
// });
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [UserController::class,'index'])->name('dashboard');
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');
    Route::get('/list', function () {
        return Inertia::render('List');
    })->name('list');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products/store',[ProductController::class,'store'])->name('products.store');
    Route::put('/products/update/{id}',[ProductController::class,'update'])->name('products.update');
    Route::delete('/products/image/{id}',[ProductController::class,'deleteImage'])->name('products.image.delete');
    Route::delete('/products/destory/{id}',[ProductController::class,'destory'])->name('products.destory');
});
