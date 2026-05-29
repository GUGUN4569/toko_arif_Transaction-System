<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\FakturController;
use App\Http\Controllers\DetailFakturController; 
use App\Http\Controllers\DetailNotaController;
 
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
Route::resource('barang', BarangController::class);
Route::resource('customer', CustomerController::class);
Route::resource('supplier', SupplierController::class);
Route::resource('pegawai', PegawaiController::class);
Route::resource('nota', NotaController::class)->parameters(['nota' => 'nota']);
Route::get('nota/{nota}/print', [NotaController::class, 'print'])->name('nota.print');
Route::resource('faktur', FakturController::class)->parameters(['faktur' => 'faktur']);
Route::get('faktur/{faktur}/print', [FakturController::class, 'print'])->name('faktur.print');
Route::resource('detail-faktur', DetailFakturController::class);
Route::resource('detail-nota', DetailNotaController::class);