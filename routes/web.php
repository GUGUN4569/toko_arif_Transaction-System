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
 
// ── Halaman Utama (Landing) ──────────────────────────
Route::get('/', function () {
    return view('index');
})->name('home');
 
// ── Auth ─────────────────────────────────────────────
Route::get('/login',   [Logincontroller::class, 'showLoginForm'])->name('login');
Route::post('/login',  [Logincontroller::class, 'login']);
Route::post('/logout', [Logincontroller::class, 'logout'])->name('logout');
 
// ── Protected Routes (harus login) ───────────────────
Route::middleware('auth')->group(function () {
 
    Route::get('/dashboard', [Dashboardcontroller::class, 'index'])->name('dashboard');
 
    Route::resource('barang',        Barangcontroller::class);
    Route::resource('customer',      Customercontroller::class);
    Route::resource('supplier',      Suppliercontroller::class);
    Route::resource('pegawai',       Pegawaicontroller::class);
    Route::resource('detail-faktur', DetailFakturController::class);
Route::resource('detail-nota', DetailNotaController::class)
    ->parameters(['detail-nota' => 'detail_nota']);
 
    // Nota + cetak
    Route::get('nota/{nota}/print', [Notacontroller::class, 'print'])->name('nota.print');
    Route::resource('nota', NotaController::class)->parameters([
    'nota' => 'nota']);
 
    // Faktur + cetak
    Route::get('faktur/{faktur}/print', [Fakturcontroller::class, 'print'])->name('faktur.print');
    Route::post('faktur/{faktur}/upload', [Fakturcontroller::class, 'upload'])->name('faktur.upload');
    Route::delete('faktur/{faktur}/delete-file', [Fakturcontroller::class, 'deleteFile'])->name('faktur.delete-file');
    Route::resource('faktur', Fakturcontroller::class);
    
});