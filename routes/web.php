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
Route::resource('faktur', FakturController::class);
Route::resource('detail-faktur', DetailFakturController::class);
Route::resource('detail-nota', DetailNotaController::class);