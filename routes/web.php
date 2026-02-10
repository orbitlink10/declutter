<?php

use App\Http\Controllers\Admin\ItemModerationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemImageController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MyListingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ListingController::class, 'home'])->name('home');
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listings/{item:slug}', [ListingController::class, 'show'])->name('listings.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/listings/{item:slug}/favorite', [FavoriteController::class, 'toggle'])
        ->middleware('throttle:40,1')
        ->name('favorites.toggle');

    Route::post('/listings/{item:slug}/reports', [ReportController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('reports.store');

    Route::prefix('my')->name('my.')->group(function () {
        Route::resource('listings', MyListingController::class)
            ->parameters(['listings' => 'item'])
            ->except('show')
            ->middleware('throttle:20,1');

        Route::post('/listings/{item}/images', [ItemImageController::class, 'store'])
            ->middleware('throttle:30,1')
            ->name('listings.images.store');

        Route::delete('/listings/{item}/images/{image}', [ItemImageController::class, 'destroy'])
            ->middleware('throttle:30,1')
            ->name('listings.images.destroy');
    });
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'is_admin'])
    ->group(function () {
        Route::get('/', fn () => redirect()->route('admin.items.index'))->name('index');
        Route::get('/items', [ItemModerationController::class, 'index'])->name('items.index');
        Route::patch('/items/{item}/remove', [ItemModerationController::class, 'remove'])->name('items.remove');
        Route::patch('/reports/{report}/status', [ItemModerationController::class, 'updateReportStatus'])->name('reports.status');
    });

require __DIR__.'/auth.php';
