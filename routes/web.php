<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PupukController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\NutrisiController;
use App\Http\Controllers\Admin\DesaController;
use App\Http\Controllers\Admin\DistribusiPupukController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin only routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // User management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/validations', [UserController::class, 'validations'])->name('users.validations');
        Route::patch('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::patch('/users/{id}/reject', [UserController::class, 'reject'])->name('users.reject');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // Master data management
        Route::resource('kategori', KategoriController::class);
        Route::resource('nutrisi', NutrisiController::class);
        Route::resource('pupuk', PupukController::class);
        Route::resource('desa', DesaController::class);

        // Stock management (stok pusat)
        Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
        Route::post('/stok', [StokController::class, 'store'])->name('stok.store');
        Route::patch('/stok/{id}', [StokController::class, 'update'])->name('stok.update');
        Route::delete('/stok/{id}', [StokController::class, 'destroy'])->name('stok.destroy');

        // Distribusi pupuk management
        Route::resource('distribusi-pupuk', DistribusiPupukController::class);
        Route::patch('/distribusi-pupuk/{id}/update-status', [DistribusiPupukController::class, 'updateStatus'])->name('distribusi-pupuk.update-status');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
