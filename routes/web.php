<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\User\ProductListController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

use Inertia\Inertia;

Route::get('/', function (UserController $userController) {
    $products = $userController->getWelcomeProducts();
    $brands = $userController->getAllBrands();
    $categories = $userController->getAllCategories();
    $services = $userController->getAllServices();
    return Inertia::render('Welcome', [
        'products' => $products,
        'brands' => $brands,
        'categories' => $categories,
        'services' => $services,
        // You can add other data here if needed
    ]);
})->name('welcome');

// Route::get('/dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/admin', function () {
//     if (Gate::allows('admin')) {
//         return view('dashboard');
//     }

//     abort(403, 'Unauthorized action.');
// });
// Route::get('/dashboard', [UserController::class,'index'])->name('dashboard');
// Route::get('/main',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('view', 'view')->name('cart.view');
    Route::post('store/{product}', 'store')->name('cart.store');
    Route::patch('update/{product}', 'update')->name('cart.update');
    Route::delete('delete/{product}', 'delete')->name('cart.delete');
});

//chekcout
//  Route::prefix('checkout')->controller(CheckoutController::class)->group((function()  {
//     Route::post('order','store')->name('checkout.store');
//     Route::get('success','success')->name('checkout.success');
//     Route::get('cancel','cancel')->name('checkout.cancel');
// }));

//routes for products list and filter
Route::prefix('product')->controller(ProductListController::class)->group(function () {
    Route::get('/', 'list')->name('products.list');
    Route::get('/view/{id}', 'view')->name('products.view');
    // Route::get('/category','category')->name('products.category');
    // Route::get('/contact','contact')->name('products.contact');
});

Route::get('/contact', [ProductListController::class, 'contact'])->name('products.contact');
Route::post('/contact/store', [ProductListController::class, 'store'])->name('products.contact.store');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');
    Route::get('/list', function () {
        return Inertia::render('List');
    })->name('list');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/show/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/image/{id}', [ProductController::class, 'deleteImage'])->name('products.image.delete');
    Route::delete('/products/destory/{id}', [ProductController::class, 'destory'])->name('products.destory');

    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('/brands/store', [BrandController::class, 'store'])->name('brands.store');
    Route::put('/brands/update/{id}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/image/{id}', [BrandController::class, 'deleteImage'])->name('brands.image.delete');
    Route::delete('/brands/destory/{id}', [BrandController::class, 'destory'])->name('brands.destory');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/image/{id}', [CategoryController::class, 'deleteImage'])->name('categories.image.delete');
    Route::delete('/categories/destory/{id}', [CategoryController::class, 'destory'])->name('categories.destory');

    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::post('/services/store', [ServiceController::class, 'store'])->name('services.store');
    Route::put('/services/update/{id}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/image/{id}', [ServiceController::class, 'deleteImage'])->name('services.image.delete');
    Route::delete('/services/destory/{id}', [ServiceController::class, 'destory'])->name('services.destory');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
    Route::put('/orders/update/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/image/{id}', [OrderController::class, 'deleteImage'])->name('orders.image.delete');
    Route::delete('/orders/destory/{id}', [OrderController::class, 'destory'])->name('orders.destory');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/update-shipment/{id}', [OrderController::class, 'updateShipment'])->name('orders.updateShipment');

    Route::get('/user/orders', [UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/user/orders/{id}', [UserOrderController::class, 'show'])->name('user.orders.show');
    Route::put('/orders/user-update-shipment/{id}', [UserOrderController::class, 'updateShipment'])->name('orders.userUpdateShipment');

    Route::prefix('checkout')->controller(CheckoutController::class)->group((function () {
        Route::post('order', 'store')->name('checkout.store');
        Route::get('success', 'success')->name('checkout.success');
        Route::get('cancel', 'cancel')->name('checkout.cancel');
        Route::post('retry-payment/{orderId}', 'retryPayment')->name('checkout.retryPayment'); // New route for retrying payment
    }));
});
