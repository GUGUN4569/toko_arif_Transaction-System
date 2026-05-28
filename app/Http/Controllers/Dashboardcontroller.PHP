<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Pegawai;
use App\Models\Nota;
use App\Models\Faktur;
use App\Models\DetailNota;
use App\Models\DetailFaktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalBarang = Barang::count();
        $totalCustomer = Customer::count();
        $totalSupplier = Supplier::count();
        $totalPegawai = Pegawai::count();
        $totalNota = Nota::count();
        $totalFaktur = Faktur::count();

        // Total nominal penjualan (seluruh nota)
        $totalPenjualan = Nota::sum('total_jumlah_nota');

        // Total nominal pembelian (seluruh faktur)
        $totalPembelian = Faktur::sum('total_jumlah_faktur');

        // Laba kotor (penjualan - pembelian)
        $labaKotor = $totalPenjualan - $totalPembelian;

        // Grafik penjualan per bulan (6 bulan terakhir)
        $penjualanPerBulan = Nota::select(
                DB::raw('DATE_FORMAT(tanggal_nota, "%Y-%m") as bulan'),
                DB::raw('SUM(total_jumlah_nota) as total')
            )
            ->where('tanggal_nota', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get()
            ->pluck('total', 'bulan')
            ->toArray();

        // Grafik pembelian per bulan (6 bulan terakhir)
        $pembelianPerBulan = Faktur::select(
                DB::raw('DATE_FORMAT(tanggal_faktur, "%Y-%m") as bulan'),
                DB::raw('SUM(total_jumlah_faktur) as total')
            )
            ->where('tanggal_faktur', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get()
            ->pluck('total', 'bulan')
            ->toArray();

        // Daftar 5 nota terbaru
        $notaTerbaru = Nota::with(['customer', 'pegawai'])
            ->orderBy('tanggal_nota', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Daftar 5 faktur terbaru
        $fakturTerbaru = Faktur::with(['supplier', 'pegawai'])
            ->orderBy('tanggal_faktur', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Barang dengan stok paling sedikit (asumsi ada kolom stok, jika tidak pakai penjualan terbanyak)
        // Karena tabel barang tidak punya stok, kita bisa tampilkan barang paling laku
        $barangTerlaris = DetailNota::select('id_barang', DB::raw('SUM(banyaknya) as total_terjual'))
            ->with('barang')
            ->groupBy('id_barang')
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBarang',
            'totalCustomer',
            'totalSupplier',
            'totalPegawai',
            'totalNota',
            'totalFaktur',
            'totalPenjualan',
            'totalPembelian',
            'labaKotor',
            'penjualanPerBulan',
            'pembelianPerBulan',
            'notaTerbaru',
            'fakturTerbaru',
            'barangTerlaris'
        ));
    }
}