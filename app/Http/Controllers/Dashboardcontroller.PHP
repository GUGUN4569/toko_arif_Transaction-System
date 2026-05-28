<?php
 
namespace App\Http\Controllers;
 
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Pegawai;
use App\Models\Nota;
use App\Models\Faktur;
 
class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_barang'    => Barang::count(),
            'total_customer'  => Customer::count(),
            'total_supplier'  => Supplier::count(),
            'total_pegawai'   => Pegawai::count(),
            'total_penjualan' => Nota::sum('total_jumlah_nota'),
            'total_pembelian' => Faktur::sum('total_jumlah_faktur'),
            'nota_terbaru'    => Nota::with('customer', 'pegawai')->latest('tanggal_nota')->take(5)->get(),
            'faktur_terbaru'  => Faktur::with('supplier', 'pegawai')->latest('tanggal_faktur')->take(5)->get(),
        ];
        return view('dashboard.index', $data);
    }
}