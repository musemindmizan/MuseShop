<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;


Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
});

require __DIR__.'/auth.php';
