<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;


Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product:slug}', [ShopController::class, 'show'])->name('product.show');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'store'])->name('cart.add');
Route::post('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/bulk-update', [CartController::class, 'bulkUpdate'])->name('cart.bulk-update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'destroy'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{productId}', [WishlistController::class, 'destroy'])->name('wishlist.remove');
    Route::post('/wishlist/move-to-cart/{productId}', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');
});

Route::middleware([AuthAdmin::class])->group(function() {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // Brands
    Route::get('/admin/brands', [BrandController::class, 'index'])->name('admin.brands');
    Route::get('/admin/brand/create', [BrandController::class, 'create'])->name('admin.brand.create');
    Route::post('/admin/brand/create', [BrandController::class, 'store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}', [BrandController::class, 'edit'])->name('admin.brand.edit');
    Route::post('/admin/brand/edit/{id}', [BrandController::class, 'update'])->name('admin.brand.update');
    Route::post('/admin/brand/delete/{id}', [BrandController::class, 'destroy'])->name('admin.brand.delete');

    // Category
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/admin/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/admin/category/create', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/admin/category/edit/{id}', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::post('/admin/category/edit/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/admin/category/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.category.delete');

    // Products
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products');
    Route::get('/admin/product/create', [ProductController::class, 'create'])->name('admin.product.create');
    Route::post('/admin/product/create', [ProductController::class, 'store'])->name('admin.product.store');
    Route::get('/admin/product/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
    Route::post('/admin/product/edit/{id}', [ProductController::class, 'update'])->name('admin.product.update');
    Route::delete('/admin/product/delete/{id}', [ProductController::class, 'destroy'])->name('admin.product.delete');
    Route::delete('/admin/products/bulk-delete', [ProductController::class, 'bulkDestroy'])->name('admin.products.bulk-delete');
    Route::get('/admin/products/export', [ProductController::class, 'export'])->name('admin.products.export');

    // Orders
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/admin/orders/export', [OrderController::class, 'export'])->name('order.export');
    Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('order.update-status');
    Route::get('/admin/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('order.invoice');
});

require __DIR__.'/auth.php';
