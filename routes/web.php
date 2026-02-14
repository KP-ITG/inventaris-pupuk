<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PupukController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\NutrisiController;
use App\Http\Controllers\Admin\DesaController;
use App\Http\Controllers\Admin\DistribusiPupukController;
use App\Http\Controllers\Admin\PermintaanController as AdminPermintaanController;
use App\Http\Controllers\Admin\ExportAllController;
use App\Http\Controllers\KepalaDesa\DashboardController as KepalaDesa_DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        // Redirect based on role
        if ($user->role === 'kepala_desa') {
            return redirect()->route('kepala-desa.dashboard');
        }

        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin and Distributor Dashboard
    Route::middleware('role:admin,distributor')
        ->get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Admin only routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // User management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/approvals', [UserController::class, 'approvals'])->name('users.approvals');
        Route::patch('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::patch('/users/{id}/reject', [UserController::class, 'reject'])->name('users.reject');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // Master data management
        Route::get('/kategori/export/pdf', [KategoriController::class, 'exportPdf'])->name('kategori.export.pdf');
        Route::get('/kategori/export/excel', [KategoriController::class, 'exportExcel'])->name('kategori.export.excel');
        Route::resource('kategori', KategoriController::class);

        Route::get('/nutrisi/export/pdf', [NutrisiController::class, 'exportPdf'])->name('nutrisi.export.pdf');
        Route::get('/nutrisi/export/excel', [NutrisiController::class, 'exportExcel'])->name('nutrisi.export.excel');
        Route::resource('nutrisi', NutrisiController::class);

        Route::get('/pupuk/export/pdf', [PupukController::class, 'exportPdf'])->name('pupuk.export.pdf');
        Route::get('/pupuk/export/excel', [PupukController::class, 'exportExcel'])->name('pupuk.export.excel');
        Route::resource('pupuk', PupukController::class);

        Route::get('/desa/export/pdf', [DesaController::class, 'exportPdf'])->name('desa.export.pdf');
        Route::get('/desa/export/excel', [DesaController::class, 'exportExcel'])->name('desa.export.excel');
        Route::resource('desa', DesaController::class);

        // Stock management (stok pusat)
        Route::get('/stok/export/pdf', [StokController::class, 'exportPdf'])->name('stok.export.pdf');
        Route::get('/stok/export/excel', [StokController::class, 'exportExcel'])->name('stok.export.excel');
        Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
        Route::post('/stok', [StokController::class, 'store'])->name('stok.store');
        Route::patch('/stok/{id}', [StokController::class, 'update'])->name('stok.update');
        Route::delete('/stok/{id}', [StokController::class, 'destroy'])->name('stok.destroy');

        // Distribusi pupuk management
        Route::get('/distribusi-pupuk/export/pdf', [DistribusiPupukController::class, 'exportPdf'])->name('distribusi-pupuk.export.pdf');
        Route::get('/distribusi-pupuk/export/excel', [DistribusiPupukController::class, 'exportExcel'])->name('distribusi-pupuk.export.excel');
        Route::patch('/distribusi-pupuk/{id}/update-status', [DistribusiPupukController::class, 'updateStatus'])->name('distribusi-pupuk.update-status');

        // Distribusi Pupuk routes (without edit & update for security)
        Route::get('/distribusi-pupuk', [DistribusiPupukController::class, 'index'])->name('distribusi-pupuk.index');
        Route::get('/distribusi-pupuk/create', [DistribusiPupukController::class, 'create'])->name('distribusi-pupuk.create');
        Route::post('/distribusi-pupuk', [DistribusiPupukController::class, 'store'])->name('distribusi-pupuk.store');
        Route::get('/distribusi-pupuk/{distribusiPupuk}', [DistribusiPupukController::class, 'show'])->name('distribusi-pupuk.show');
        Route::delete('/distribusi-pupuk/{distribusiPupuk}', [DistribusiPupukController::class, 'destroy'])->name('distribusi-pupuk.destroy');

        // Export All Data
        Route::get('/export-all', [ExportAllController::class, 'index'])->name('export-all.index');
        Route::get('/export-all/pdf', [ExportAllController::class, 'exportPdf'])->name('export-all.pdf');
        Route::get('/export-all/excel', [ExportAllController::class, 'exportExcel'])->name('export-all.excel');

        // Permintaan Distribusi management
        Route::get('/permintaan', [AdminPermintaanController::class, 'index'])->name('permintaan.index');
        Route::patch('/permintaan/{id}/approve', [AdminPermintaanController::class, 'approve'])->name('permintaan.approve');
        Route::patch('/permintaan/{id}/reject', [AdminPermintaanController::class, 'reject'])->name('permintaan.reject');
    });

    // Kepala Desa routes
    Route::middleware('role:kepala_desa')->prefix('kepala-desa')->name('kepala-desa.')->group(function () {
        Route::get('/dashboard', [KepalaDesa_DashboardController::class, 'index'])->name('dashboard');
        Route::get('/ajukan-permintaan', [KepalaDesa_DashboardController::class, 'ajukanPermintaan'])->name('ajukan-permintaan');
        Route::get('/histori-permintaan', [KepalaDesa_DashboardController::class, 'historiPermintaan'])->name('histori-permintaan');
        Route::post('/permintaan', [KepalaDesa_DashboardController::class, 'store'])->name('permintaan.store');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
