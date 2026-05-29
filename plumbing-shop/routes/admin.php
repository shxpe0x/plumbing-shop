<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('/{section}', [SectionController::class, 'show'])
    ->where('section', 'products|categories|orders|promotions|banners|pages|articles|reviews|users|settings|delivery-methods|payment-methods|regions|tariffs|callbacks|chat|reports')
    ->name('admin.section');
