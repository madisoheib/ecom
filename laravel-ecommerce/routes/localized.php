<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;

// English Routes
Route::prefix('en')->group(function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('en.home');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('en.products.index');
    Route::get('/product/{categorySlug}/{productSlug}', [ProductController::class, 'show'])->name('en.products.show');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('en.categories.index');
    Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('en.categories.show');

    // Brands
    Route::get('/brands', [BrandController::class, 'index'])->name('en.brands.index');
    Route::get('/brand/{slug}', [BrandController::class, 'show'])->name('en.brands.show');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('en.cart.index');

    // Auth
    Route::get('/login', [AuthController::class, 'showLogin'])->name('en.login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('en.register');

    // User Dashboard
    Route::middleware('auth')->group(function () {
        Route::get('/my-account', [AuthController::class, 'dashboard'])->name('en.dashboard');
        Route::get('/my-orders', [AuthController::class, 'orders'])->name('en.user.orders');
        Route::get('/profile', [AuthController::class, 'profile'])->name('en.user.profile');
    });
});

// French Routes
Route::prefix('fr')->group(function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('fr.home');

    // Products
    Route::get('/produits', [ProductController::class, 'index'])->name('fr.products.index');
    Route::get('/produit/{categorySlug}/{productSlug}', [ProductController::class, 'show'])->name('fr.products.show');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('fr.categories.index');
    Route::get('/categorie/{slug}', [CategoryController::class, 'show'])->name('fr.categories.show');

    // Brands
    Route::get('/marques', [BrandController::class, 'index'])->name('fr.brands.index');
    Route::get('/marque/{slug}', [BrandController::class, 'show'])->name('fr.brands.show');

    // Cart
    Route::get('/panier', [CartController::class, 'index'])->name('fr.cart.index');

    // Auth
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('fr.login');
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('fr.register');

    // User Dashboard
    Route::middleware('auth')->group(function () {
        Route::get('/mon-compte', [AuthController::class, 'dashboard'])->name('fr.dashboard');
        Route::get('/mes-commandes', [AuthController::class, 'orders'])->name('fr.user.orders');
        Route::get('/profil', [AuthController::class, 'profile'])->name('fr.user.profile');
    });
});

// Arabic Routes
Route::prefix('ar')->group(function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('ar.home');

    // Products
    Route::get('/منتجات', [ProductController::class, 'index'])->name('ar.products.index');
    Route::get('/منتج/{categorySlug}/{productSlug}', [ProductController::class, 'show'])->name('ar.products.show');

    // Categories
    Route::get('/فئات', [CategoryController::class, 'index'])->name('ar.categories.index');
    Route::get('/فئة/{slug}', [CategoryController::class, 'show'])->name('ar.categories.show');

    // Brands
    Route::get('/علامات-تجارية', [BrandController::class, 'index'])->name('ar.brands.index');
    Route::get('/علامة-تجارية/{slug}', [BrandController::class, 'show'])->name('ar.brands.show');

    // Cart
    Route::get('/عربة-التسوق', [CartController::class, 'index'])->name('ar.cart.index');

    // Auth
    Route::get('/تسجيل-الدخول', [AuthController::class, 'showLogin'])->name('ar.login');
    Route::get('/إنشاء-حساب', [AuthController::class, 'showRegister'])->name('ar.register');

    // User Dashboard
    Route::middleware('auth')->group(function () {
        Route::get('/حسابي', [AuthController::class, 'dashboard'])->name('ar.dashboard');
        Route::get('/طلباتي', [AuthController::class, 'orders'])->name('ar.user.orders');
        Route::get('/الملف-الشخصي', [AuthController::class, 'profile'])->name('ar.user.profile');
    });
});

// Redirect root to default language
Route::get('/', function () {
    $locale = session('locale', 'en');
    return redirect("/{$locale}");
});