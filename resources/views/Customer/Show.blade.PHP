@extends('layouts.app')
@section('title', 'Detail Customer')
 
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">Detail Customer</span>
        <div class="flex gap-2">
            <a href="{{ route('customer.edit', $customer) }}" class="btn btn-secondary btn-sm">Edit</a>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <table style="width:100%">
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px;width:130px">ID Customer</td><td><span class="badge badge-blue font-mono">{{ $customer->id_customer }}</span></td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Nama</td><td><strong>{{ $customer->nama_customer }}</strong></td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Kontak</td><td>{{ $customer->kontak ?? '—' }}</td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Total Nota</td><td><span class="badge badge-green">{{ $customer->nota->count() }} transaksi</span></td></tr>
        </table>
    </div>
</div>
@endsection