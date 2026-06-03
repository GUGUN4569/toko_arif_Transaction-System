@extends('layouts.app')
@section('title', 'Nota Penjualan')
 
@section('topbar-actions')
    <a href="{{ route('nota.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Buat Nota
    </a>
@endsection
 
@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID Nota</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Pegawai</th>
                    <th>Tanda Terima</th>
                    <th class="text-right">Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notas as $n)
                <tr>
                    <td><span class="badge badge-green font-mono">{{ $n->id_nota }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($n->tanggal_nota)->format('d M Y') }}</td>
                    <td>{{ $n->customer->nama_customer ?? '-' }}</td>
                    <td>{{ $n->pegawai->nama_pegawai ?? '-' }}</td>
                    <td>{{ $n->tanda_terima ?? '-' }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($n->total_jumlah_nota, 0, ',', '.') }}</strong></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('nota.show', $n) }}" class="btn btn-secondary btn-sm">Detail</a>
                            <a href="{{ route('nota.edit', $n) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <a href="{{ route('nota.print', $n->id_nota) }}" target="_blank" class="btn btn-primary btn-sm">🖨️ Cetak</a>
                            <form action="{{ route('nota.destroy', $n) }}" method="POST" onsubmit="return confirm('Hapus nota ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:32px;" class="text-muted">Belum ada nota penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection