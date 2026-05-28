@extends('layouts.app')
@section('title', 'Detail Barang')
 
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header">
        <span class="card-title">Detail Barang</span>
        <div class="flex gap-2">
            <a href="{{ route('barang.edit', $barang) }}" class="btn btn-secondary btn-sm">Edit</a>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <table style="width:100%;border-collapse:collapse">
            <tr><td style="padding:10px 0;color:var(--muted);width:130px;font-size:13px">ID Barang</td><td><span class="badge badge-blue font-mono">{{ $barang->id_barang }}</span></td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Nama Barang</td><td><strong>{{ $barang->nama_barang }}</strong></td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Harga</td><td style="color:var(--green)">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Diskon</td><td>{{ $barang->disc > 0 ? $barang->disc.'%' : '—' }}</td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Satuan</td><td>{{ $barang->satuan ?? '—' }}</td></tr>
        </table>
    </div>
</div>
@endsection