@extends('layouts.app')
@section('title', 'Detail Supplier')
 
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">Detail Supplier</span>
        <div class="flex gap-2">
            <a href="{{ route('supplier.edit', $supplier) }}" class="btn btn-secondary btn-sm">Edit</a>
            <a href="{{ route('supplier.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <table style="width:100%">
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px;width:140px">ID Supplier</td><td><span class="badge badge-blue font-mono">{{ $supplier->id_supplier }}</span></td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Nama</td><td><strong>{{ $supplier->nama_supplier }}</strong></td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">No. Telepon</td><td>{{ $supplier->no_telp_supplier ?? '—' }}</td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Alamat</td><td>{{ $supplier->alamat_supplier ?? '—' }}</td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Total Faktur</td><td><span class="badge badge-gold">{{ $supplier->faktur->count() }} faktur</span></td></tr>
        </table>
    </div>
</div>
@endsection