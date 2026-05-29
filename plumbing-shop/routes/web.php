<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');

Route::controller(PageController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/delivery', 'delivery')->name('delivery');
    Route::get('/warranty', 'warranty')->name('warranty');
    Route::get('/contacts', 'contacts')->name('contacts');
});

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
