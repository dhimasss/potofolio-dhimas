<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public (User-Facing)
|--------------------------------------------------------------------------
| Halaman storytelling untuk pengunjung. Tidak perlu login.
*/

Route::get('/', [PortfolioController::class, 'index'])->name('home');

// {project} di-resolve lewat slug (lihat Project::getRouteKeyName())
Route::get('/projects/{project}', [PortfolioController::class, 'show'])->name('projects.show');

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
| Semua URL diawali /admin dan nama route diawali "admin.".
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Hanya untuk tamu (belum login). User yang sudah login diarahkan ke dashboard.
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');

        // throttle:5,1 = maksimal 5 percobaan login per menit (anti brute-force)
        Route::post('/login', [AuthController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    // Hanya untuk admin yang sudah login. Tamu diarahkan ke /admin/login.
    Route::middleware('auth')->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        // index, create, store, edit, update, destroy -> admin.projects.*
        // "show" tidak dipakai: admin bisa melihat hasilnya lewat halaman publik.
        Route::resource('projects', ProjectController::class)->except('show');

        // Singleton: hanya ada satu biodata, jadi URL tanpa {id} -> /admin/about, /admin/about/edit, ...
        Route::singleton('about', AboutController::class)->creatable()->destroyable();

        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    });
});
