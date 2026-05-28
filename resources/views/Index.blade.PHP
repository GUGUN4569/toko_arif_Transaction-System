@extends('layouts.app')
@section('title', 'Dashboard')
 
@section('content')
<div class="stat-grid">
    <div class="stat-card gold">
        <div class="stat-label">Total Barang</div>
        <div class="stat-value">{{ $total_barang }}</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Total Customer</div>
        <div class="stat-value">{{ $total_customer }}</div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Total Supplier</div>
        <div class="stat-value">{{ $total_supplier }}</div>
    </div>
    <div class="stat-card red">
        <div class="stat-label">Total Pegawai</div>
        <div class="stat-value">{{ $total_pegawai }}</div>
    </div>
</div>
 
<div class="stat-grid" style="margin-bottom:28px">
    <div class="stat-card green" style="grid-column: span 1">
        <div class="stat-label">Total Penjualan</div>
        <div class="stat-value" style="font-size:22px;">Rp {{ number_format($total_penjualan, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card gold" style="grid-column: span 1">
        <div class="stat-label">Total Pembelian</div>
        <div class="stat-value" style="font-size:22px;">Rp {{ number_format($total_pembelian, 0, ',', '.') }}</div>
    </div>
</div>
 
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Nota Penjualan Terbaru</span>
            <a href="{{ route('nota.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nota_terbaru as $n)
                    <tr>
                        <td><span class="badge badge-green font-mono">{{ $n->id_nota }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($n->tanggal_nota)->format('d M Y') }}</td>
                        <td>{{ $n->customer->nama_customer ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($n->total_jumlah_nota, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-muted" style="text-align:center;padding:24px;">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
 
    <div class="card">
        <div class="card-header">
            <span class="card-title">Faktur Pembelian Terbaru</span>
            <a href="{{ route('faktur.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No Faktur</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faktur_terbaru as $f)
                    <tr>
                        <td><span class="badge badge-gold font-mono">{{ $f->no_faktur }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($f->tanggal_faktur)->format('d M Y') }}</td>
                        <td>{{ $f->supplier->nama_supplier ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($f->total_jumlah_faktur, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-muted" style="text-align:center;padding:24px;">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection