<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboardcontroller;
use App\Http\Controllers\Barangcontroller;
use App\Http\Controllers\Customercontroller;
use App\Http\Controllers\Suppliercontroller;
use App\Http\Controllers\Pegawaicontroller;
use App\Http\Controllers\Notacontroller;
use App\Http\Controllers\Fakturcontroller;
use App\Http\Controllers\DetailFakturController;
use App\Http\Controllers\DetailNotaController;
use App\Http\Controllers\Logincontroller;
use App\Http\Controllers\UserController;

// ── Halaman Utama (Landing) ──────────────────────────
Route::get('/', function () {
    return view('index');
})->name('home');

// ── Auth ─────────────────────────────────────────────
Route::get('/login',   [Logincontroller::class, 'showLoginForm'])->name('login');
Route::post('/login',  [Logincontroller::class, 'login']);
Route::post('/logout', [Logincontroller::class, 'logout'])->name('logout');

// ── Protected Routes (semua role bisa akses) ─────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [Dashboardcontroller::class, 'index'])->name('dashboard');

    // Nota Penjualan — kasir & admin
    Route::get('nota/{nota}/print', [Notacontroller::class, 'print'])->name('nota.print');

    // Resource utama TANPA edit/update/destroy (didaftarkan manual di bawah dengan middleware kunci)
    Route::resource('nota', Notacontroller::class)
        ->parameters(['nota' => 'nota'])
        ->except(['edit', 'update', 'destroy']);

    Route::resource('detail-nota', DetailNotaController::class)
        ->parameters(['detail-nota' => 'detail_nota'])
        ->except(['edit', 'update', 'destroy', 'store']);

    // ── Kunci akses ubah/hapus untuk transaksi yang sudah lewat 14 hari ──
    Route::middleware('lock.transaction')->group(function () {
        Route::get('nota/{nota}/edit', [Notacontroller::class, 'edit'])->name('nota.edit');
        Route::put('nota/{nota}', [Notacontroller::class, 'update'])->name('nota.update');
        Route::delete('nota/{nota}', [Notacontroller::class, 'destroy'])->name('nota.destroy');

        Route::post('detail-nota', [DetailNotaController::class, 'store'])->name('detail-nota.store');
        Route::get('detail-nota/{detail_nota}/edit', [DetailNotaController::class, 'edit'])->name('detail-nota.edit');
        Route::put('detail-nota/{detail_nota}', [DetailNotaController::class, 'update'])->name('detail-nota.update');
        Route::delete('detail-nota/{detail_nota}', [DetailNotaController::class, 'destroy'])->name('detail-nota.destroy');
    });

    // ── Admin Only ────────────────────────────────────
    Route::middleware('admin')->group(function () {

        // Master Data
        Route::resource('barang',   Barangcontroller::class);
        Route::resource('customer', Customercontroller::class);
        Route::resource('supplier', Suppliercontroller::class);
        Route::resource('pegawai',  Pegawaicontroller::class);

        // Faktur Pembelian
        Route::get('faktur/{faktur}/print', [Fakturcontroller::class, 'print'])->name('faktur.print');
        Route::post('faktur/{faktur}/upload', [Fakturcontroller::class, 'upload'])->name('faktur.upload');
        Route::delete('faktur/{faktur}/delete-file', [Fakturcontroller::class, 'deleteFile'])->name('faktur.delete-file');

        Route::resource('faktur', Fakturcontroller::class)
            ->except(['edit', 'update', 'destroy']);
        Route::resource('detail-faktur', DetailFakturController::class)
            ->parameters(['detail-faktur' => 'detail_faktur'])
            ->except(['edit', 'update', 'destroy', 'store']);

        // ── Kunci akses ubah/hapus untuk faktur yang sudah lewat 14 hari ──
        Route::middleware('lock.transaction')->group(function () {
            Route::get('faktur/{faktur}/edit', [Fakturcontroller::class, 'edit'])->name('faktur.edit');
            Route::put('faktur/{faktur}', [Fakturcontroller::class, 'update'])->name('faktur.update');
            Route::delete('faktur/{faktur}', [Fakturcontroller::class, 'destroy'])->name('faktur.destroy');

            Route::post('detail-faktur', [DetailFakturController::class, 'store'])->name('detail-faktur.store');
            Route::get('detail-faktur/{detail_faktur}/edit', [DetailFakturController::class, 'edit'])->name('detail-faktur.edit');
            Route::put('detail-faktur/{detail_faktur}', [DetailFakturController::class, 'update'])->name('detail-faktur.update');
            Route::delete('detail-faktur/{detail_faktur}', [DetailFakturController::class, 'destroy'])->name('detail-faktur.destroy');
        });

        // Manajemen User
        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });
});