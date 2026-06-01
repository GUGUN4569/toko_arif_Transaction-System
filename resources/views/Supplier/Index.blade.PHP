@extends('layouts.app')
@section('title', 'Data Supplier')
 
@section('topbar-actions')
    <a href="{{ route('supplier.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Supplier
    </a>
@endsection
 
@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>No. Telp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $s)
                <tr>
                    <td><span class="badge badge-gold font-mono">{{ $s->id_supplier }}</span></td>
                    <td><strong>{{ $s->nama_supplier }}</strong></td>
                    <td>{{ $s->alamat_supplier ?? '-' }}</td>
                    <td>{{ $s->no_telp_supplier ?? '-' }}</td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('supplier.edit', $s) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('supplier.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus supplier ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:32px;" class="text-muted">Belum ada data supplier.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        @foreach($suppliers->links()->elements[0] as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $suppliers->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
    </div>
</div>
@endsection