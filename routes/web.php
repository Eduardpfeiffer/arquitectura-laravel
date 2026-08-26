<?php

use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [PortfolioController::class, 'index'])->name('admin.portfolio.index');
    Route::post('/admin', [PortfolioController::class, 'store'])->name('admin.portfolio.store');
    Route::put('/admin/{portfolioProject}', [PortfolioController::class, 'update'])->name('admin.portfolio.update');
    Route::delete('/admin/{portfolioProject}', [PortfolioController::class, 'destroy'])->name('admin.portfolio.destroy');

    Route::get('/admin/servicios', [ServiceController::class, 'index'])->name('admin.services.index');
    Route::post('/admin/servicios', [ServiceController::class, 'store'])->name('admin.services.store');
    Route::put('/admin/servicios/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/admin/servicios/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
});

require __DIR__.'/auth.php';
