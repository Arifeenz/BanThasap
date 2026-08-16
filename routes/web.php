<?php

use App\Http\Controllers\AttractionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\VillageController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, SetLocale::SUPPORTED_LOCALES, true)) {
        session(['locale' => $locale]);
    }

    return back();
})->name('lang.switch');

Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::get('/', [HomeController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/attractions', [AttractionController::class, 'index']);
Route::get('/attractions/{slug}', [AttractionController::class, 'show']);
Route::get('/map', [MapController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);
Route::get('/villages', [VillageController::class, 'index']);
Route::get('/villages/{slug}', [VillageController::class, 'show']);