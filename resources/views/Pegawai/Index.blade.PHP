@extends('layouts.app')
@section('title', 'Data Pegawai')
 
@section('topbar-actions')
    <a href="{{ route('pegawai.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Pegawai
    </a>
@endsection
 
@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID Pegawai</th>
                    <th>Nama Pegawai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawais as $p)
                <tr>
                    <td><span class="badge badge-muted font-mono">{{ $p->id_pegawai }}</span></td>
                    <td><strong>{{ $p->nama_pegawai }}</strong></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('pegawai.edit', $p) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('pegawai.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus pegawai ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;padding:32px;" class="text-muted">Belum ada data pegawai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        @foreach($pegawais->links()->elements[0] as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $pegawais->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
    </div>
</div>
@endsection