@extends('layouts.app')
@section('title', 'Data Barang')
 
@section('topbar-actions')
    <a href="{{ route('barang.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Barang
    </a>
@endsection
 
@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $b)
                <tr>
                    <td><span class="badge badge-blue font-mono">{{ $b->id_barang }}</span></td>
                    <td><strong>{{ $b->nama_barang }}</strong></td>
                    <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                    <td>
                        @if($b->disc > 0)
                            <span class="badge badge-gold">{{ $b->disc }}%</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $b->satuan ?? '-' }}</td>
                    <td>
                        <div class="flex gap-2">
 
                            <a href="{{ route('barang.edit', $b) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('barang.destroy', $b) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:32px;" class="text-muted">Belum ada data barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        @foreach($barangs->links()->elements[0] as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $barangs->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
    </div>
</div>
@endsection