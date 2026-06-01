@extends('layouts.app')
@section('title', 'Data Customer')
 
@section('topbar-actions')
    <a href="{{ route('customer.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Customer
    </a>
@endsection
 
@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID Customer</th>
                    <th>Nama Customer</th>
                    <th>Kontak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                <tr>
                    <td><span class="badge badge-green font-mono">{{ $c->id_customer }}</span></td>
                    <td><strong>{{ $c->nama_customer }}</strong></td>
                    <td>{{ $c->kontak ?? '-' }}</td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('customer.edit', $c) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('customer.destroy', $c) }}" method="POST" onsubmit="return confirm('Hapus customer ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:32px;" class="text-muted">Belum ada data customer.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        @foreach($customers->links()->elements[0] as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $customers->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
    </div>
</div>
@endsection