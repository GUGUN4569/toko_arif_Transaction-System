@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- ── Stat Cards ── --}}
<div class="stat-grid">

    <a href="{{ route('barang.index') }}" class="stat-card blue">
        <div class="stat-label">Total Barang</div>
        <div class="stat-value">{{ number_format($totalBarang, 0, ',', '.') }}</div>
    </a>

    <a href="{{ route('customer.index') }}" class="stat-card green">
        <div class="stat-label">Total Customer</div>
        <div class="stat-value">{{ number_format($totalCustomer, 0, ',', '.') }}</div>
    </a>

    <a href="{{ route('supplier.index') }}" class="stat-card gold">
        <div class="stat-label">Total Supplier</div>
        <div class="stat-value">{{ number_format($totalSupplier, 0, ',', '.') }}</div>
    </a>

    <a href="{{ route('pegawai.index') }}" class="stat-card teal">
        <div class="stat-label">Total Pegawai</div>
        <div class="stat-value">{{ number_format($totalPegawai, 0, ',', '.') }}</div>
    </a>

</div>

{{-- ── Top 5 Barang Terlaris ── --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">🏆 Top 5 Barang Terlaris</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:44px">#</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th class="text-right">Terjual</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @php $maxTerjual = $barangTerlaris->max('total_terjual') ?: 1; @endphp
                @forelse($barangTerlaris as $i => $item)
                @php
                    $rankClass = match($i) { 0 => 'rank-1', 1 => 'rank-2', 2 => 'rank-3', default => 'rank-n' };
                    $barPct = round(($item->total_terjual / $maxTerjual) * 100);
                @endphp
                <tr>
                    <td><span class="rank-badge {{ $rankClass }}">{{ $i + 1 }}</span></td>
                    <td><span class="badge badge-muted" style="font-family:monospace;">{{ $item->id_barang }}</span></td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td class="td-amount">{{ number_format($item->total_terjual, 0, ',', '.') }}</td>
                    <td><div class="bar-bg"><div class="bar-fill" style="width:{{ $barPct }}%"></div></div></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--muted);padding:24px;">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const penjualanData = @json($penjualanPerBulan);
    const pembelianData = @json($pembelianPerBulan);

    const allLabels = [...new Set([
        ...Object.keys(penjualanData),
        ...Object.keys(pembelianData)
    ])].sort();

    const penjualanValues = allLabels.map(l => penjualanData[l] || 0);
    const pembelianValues = allLabels.map(l => pembelianData[l] || 0);

    Chart.defaults.color = '#6b7280';
    Chart.defaults.borderColor = '#252a38';

    const baseOpts = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a1e28',
                borderColor: '#2e3447',
                borderWidth: 1,
                titleColor: '#e8eaf0',
                bodyColor: '#9ca3af',
                padding: 10,
                callbacks: {
                    label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID')
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 11 } }
            },
            y: {
                beginAtZero: true,
                grid: { color: '#1a1e28' },
                ticks: {
                    font: { size: 11 },
                    callback: v => v >= 1e6 ? 'Rp '+(v/1e6).toFixed(0)+'jt' : 'Rp '+v.toLocaleString('id-ID')
                }
            }
        }
    };

    new Chart(document.getElementById('penjualanChart'), {
        type: 'line',
        data: {
            labels: allLabels,
            datasets: [{
                data: penjualanValues,
                borderColor: '#a855f7',
                backgroundColor: 'rgba(168,85,247,0.08)',
                borderWidth: 2,
                pointBackgroundColor: '#a855f7',
                pointBorderColor: '#13161e',
                pointBorderWidth: 2,
                pointRadius: 4,
                tension: 0.35,
                fill: true
            }]
        },
        options: baseOpts
    });

    new Chart(document.getElementById('pembelianChart'), {
        type: 'line',
        data: {
            labels: allLabels,
            datasets: [{
                data: pembelianValues,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.08)',
                borderWidth: 2,
                pointBackgroundColor: '#ef4444',
                pointBorderColor: '#13161e',
                pointBorderWidth: 2,
                pointRadius: 4,
                tension: 0.35,
                fill: true
            }]
        },
        options: baseOpts
    });
});
</script>
@endpush