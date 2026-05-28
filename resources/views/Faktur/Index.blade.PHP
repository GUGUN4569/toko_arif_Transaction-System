@extends('layouts.app')
@section('title', 'Faktur Pembelian')
 
@section('topbar-actions')
    <a href="{{ route('faktur.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Buat Faktur
    </a>
@endsection
 
@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID Faktur</th>
                    <th>No. Faktur</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Pegawai</th>
                    <th class="text-right">Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fakturs as $f)
                <tr>
                    <td><span class="badge badge-blue font-mono">{{ $f->id_faktur }}</span></td>
                    <td><span class="badge badge-gold font-mono">{{ $f->no_faktur }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($f->tanggal_faktur)->format('d M Y') }}</td>
                    <td>{{ $f->supplier->nama_supplier ?? '-' }}</td>
                    <td>{{ $f->pegawai->nama_pegawai ?? '-' }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($f->total_jumlah_faktur, 0, ',', '.') }}</strong></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('faktur.show', $f) }}" class="btn btn-secondary btn-sm">Detail</a>
                            <a href="{{ route('faktur.edit', $f) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('faktur.destroy', $f) }}" method="POST" onsubmit="return confirm('Hapus faktur ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:32px;" class="text-muted">Belum ada faktur pembelian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection