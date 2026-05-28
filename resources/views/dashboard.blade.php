@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard Toko Arif</h1>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="text-sm text-gray-500">Total Barang</div>
            <div class="text-2xl font-bold">{{ number_format($totalBarang, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="text-sm text-gray-500">Total Customer</div>
            <div class="text-2xl font-bold">{{ number_format($totalCustomer, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="text-sm text-gray-500">Total Supplier</div>
            <div class="text-2xl font-bold">{{ number_format($totalSupplier, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <div class="text-sm text-gray-500">Total Pegawai</div>
            <div class="text-2xl font-bold">{{ number_format($totalPegawai, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-indigo-500">
            <div class="text-sm text-gray-500">Nota Penjualan</div>
            <div class="text-2xl font-bold">{{ number_format($totalNota, 0, ',', '.') }}</div>
            <div class="text-xs text-gray-500">Total: Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="text-sm text-gray-500">Faktur Pembelian</div>
            <div class="text-2xl font-bold">{{ number_format($totalFaktur, 0, ',', '.') }}</div>
            <div class="text-xs text-gray-500">Total: Rp {{ number_format($totalPembelian, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-teal-500">
            <div class="text-sm text-gray-500">Laba Kotor</div>
            <div class="text-2xl font-bold text-green-600">Rp {{ number_format($labaKotor, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Grafik Penjualan & Pembelian -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold mb-3">Penjualan per Bulan (6 bulan terakhir)</h2>
            <canvas id="penjualanChart" height="200"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold mb-3">Pembelian per Bulan (6 bulan terakhir)</h2>
            <canvas id="pembelianChart" height="200"></canvas>
        </div>
    </div>

    <!-- Transaksi Terbaru & Barang Terlaris -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold mb-3">5 Nota Penjualan Terbaru</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">No Nota</th>
                            <th class="px-3 py-2 text-left">Tanggal</th>
                            <th class="px-3 py-2 text-left">Customer</th>
                            <th class="px-3 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notaTerbaru as $nota)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $nota->id_nota }}</td>
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($nota->tanggal_nota)->format('d/m/Y') }}</td>
                            <td class="px-3 py-2">{{ $nota->customer->nama_customer ?? '-' }}</td>
                            <td class="px-3 py-2 text-right">Rp {{ number_format($nota->total_jumlah_nota, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold mb-3">5 Faktur Pembelian Terbaru</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">No Faktur</th>
                            <th class="px-3 py-2 text-left">Tanggal</th>
                            <th class="px-3 py-2 text-left">Supplier</th>
                            <th class="px-3 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fakturTerbaru as $faktur)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $faktur->id_faktur }}</td>
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($faktur->tanggal_faktur)->format('d/m/Y') }}</td>
                            <td class="px-3 py-2">{{ $faktur->supplier->nama_supplier ?? '-' }}</td>
                            <td class="px-3 py-2 text-right">Rp {{ number_format($faktur->total_jumlah_faktur, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Barang Terlaris -->
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold mb-3">Top 5 Barang Terlaris</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left">Kode Barang</th>
                        <th class="px-3 py-2 text-left">Nama Barang</th>
                        <th class="px-3 py-2 text-right">Total Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangTerlaris as $item)
                    <tr class="border-b">
                        <td class="px-3 py-2">{{ $item->id_barang }}</td>
                        <td class="px-3 py-2">{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($item->total_terjual, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3">Belum ada data penjualan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data penjualan
        const penjualanData = @json($penjualanPerBulan);
        const pembelianData = @json($pembelianPerBulan);
        
        const labels = Object.keys({...penjualanData, ...pembelianData}).sort();
        
        const penjualanValues = labels.map(label => penjualanData[label] || 0);
        const pembelianValues = labels.map(label => pembelianData[label] || 0);
        
        // Chart Penjualan
        const ctxPenjualan = document.getElementById('penjualanChart').getContext('2d');
        new Chart(ctxPenjualan, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: penjualanValues,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
        
        // Chart Pembelian
        const ctxPembelian = document.getElementById('pembelianChart').getContext('2d');
        new Chart(ctxPembelian, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pembelian (Rp)',
                    data: pembelianValues,
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush