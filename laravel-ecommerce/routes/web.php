<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestOrderController;
use App\Http\Controllers\SitemapController;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/produits', [ProductController::class, 'index'])->name('products.index');
Route::get('/produit/{categorySlug}/{productSlug}', [ProductController::class, 'show'])->name('products.show');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categorie/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Brands
Route::get('/marques', [BrandController::class, 'index'])->name('brands.index');
Route::get('/marque/{slug}', [BrandController::class, 'show'])->name('brands.show');

// Cart
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::get('/panier/data', [CartController::class, 'getCartData'])->name('cart.data');
Route::post('/panier/ajouter', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/modifier/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/supprimer/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/panier/vider', [CartController::class, 'clear'])->name('cart.clear');

// Guest Orders
Route::post('/guest-order', [GuestOrderController::class, 'store'])->name('guest-order.store');
Route::get('/refresh-captcha', [GuestOrderController::class, 'refreshCaptcha'])->name('refresh.captcha');

// Authentication
Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login']);
Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
Route::post('/inscription', [AuthController::class, 'register']);
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

// User Dashboard (protected routes)
Route::middleware('auth')->group(function () {
    Route::get('/mon-compte', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/mes-commandes', [AuthController::class, 'orders'])->name('user.orders');
    Route::get('/commande/{order}', [AuthController::class, 'orderDetails'])->name('user.order.details');
    Route::get('/profil', [AuthController::class, 'profile'])->name('user.profile');
    Route::put('/profil', [AuthController::class, 'updateProfile'])->name('user.profile.update');
});

// Checkout (protected)
Route::middleware('auth')->group(function () {
    Route::get('/commande', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/commande', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/commande/confirmation/{order}', [CartController::class, 'confirmation'])->name('checkout.confirmation');
});

// SEO routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
