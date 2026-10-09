<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PaketWisataController as PublicPaketController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinasiController;
use App\Http\Controllers\Admin\RuteTolController;
use App\Http\Controllers\Admin\ArmadaController;
use App\Http\Controllers\Admin\ProposalController;
use App\Http\Controllers\Admin\PaketWisataController as AdminPaketController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Auth\LoginController;


Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
    Route::get('/paket-wisata', [PublicPaketController::class, 'index'])->name('paket.index'); // Menjadi 'frontend.paket.index'
    Route::get('/paket-wisata/{paketWisata:slug}', [PublicPaketController::class, 'show'])->name('paket.show'); // Menjadi 'frontend.paket.show'
    Route::get('/galeri', [HomeController::class, 'galeri'])->name('galeri');
    Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');
});

// ==================== 2. AUTHENTICATION ROUTES ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->prefix('admin')->as('admin.')->group(function () {

    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Master Data
    Route::resource('destinasi', DestinasiController::class)->scoped(['destinasi' => 'uuid']);
    Route::resource('rute-tol', RuteTolController::class)->scoped(['rute_tol' => 'uuid']);
    Route::resource('armada', ArmadaController::class)->scoped(['armada' => 'uuid']);

    // Management Paket Wisata & Galeri
    Route::resource('paket-wisata', AdminPaketController::class)
        ->parameters(['paket-wisata' => 'paket_wisata'])
        ->scoped(['paket_wisata' => 'uuid']);
    Route::resource('galeri', GaleriController::class)->scoped(['galeri' => 'uuid']);

    Route::prefix('proposal')->as('proposal.')->group(function () {
        Route::get('/', [ProposalController::class, 'index'])->name('index');
        Route::get('/create', [ProposalController::class, 'create'])->name('create');
        Route::post('/', [ProposalController::class, 'store'])->name('store');
        Route::get('/{proposal:uuid}', [ProposalController::class, 'show'])->name('show');
        Route::get('/{proposal:uuid}/edit', [ProposalController::class, 'edit'])->name('edit');
        Route::put('/{proposal:uuid}', [ProposalController::class, 'update'])->name('update');
        Route::delete('/{proposal:uuid}', [ProposalController::class, 'destroy'])->name('destroy');

        // Route Akses API/AJAX untuk kalkulasi otomatis di form
        Route::post('/kalkulasi-ajax', [ProposalController::class, 'hitungSimulasi'])->name('kalkulasi.ajax');

        // Route Export PDF Proposal
        Route::get('/{proposal:uuid}/export-pdf', [ProposalController::class, 'exportPdf'])->name('export-pdf');
    });
});

// Auth Routes (Disediakan oleh Laravel Breeze / Fortify)
// require __DIR__ . '/auth.php';
