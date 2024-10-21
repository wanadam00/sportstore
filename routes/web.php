<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\ProductListController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController;
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
Route::get('/dashboard', [UserController::class,'index'])->name('dashboard');
// Route::get('/main',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('view','view')->name('cart.view');
    Route::post('store/{product}','store')->name('cart.store');
    Route::patch('update/{product}','update')->name('cart.update');
    Route::delete('delete/{product}','delete')->name('cart.delete');
});

 //chekcout
 Route::prefix('checkout')->controller(CheckoutController::class)->group((function()  {
    Route::post('order','store')->name('checkout.store');
    Route::get('success','success')->name('checkout.success');
    Route::get('cancel','cancel')->name('checkout.cancel');
}));

//routes for products list and filter
Route::prefix('product')->controller(ProductListController::class)->group(function ()  {
    Route::get('/','list')->name('products.list');

});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', [UserController::class,'index'])->name('dashboard');
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
