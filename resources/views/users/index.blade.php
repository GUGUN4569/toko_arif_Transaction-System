@extends('layouts.app')
@section('title', 'Manajemen User')
 
@section('topbar-actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah User
    </a>
@endsection
 
@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>
                        <strong>{{ $u->name }}</strong>
                        @if($u->id === auth()->id())
                            <span class="badge badge-blue" style="margin-left:6px">Anda</span>
                        @endif
                    </td>
                    <td class="text-muted">{{ $u->email }}</td>
                    <td>
                        @if($u->role === 'admin')
                            <span class="badge badge-gold">Admin</span>
                        @else
                            <span class="badge badge-green">Kasir</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-secondary btn-sm">Edit</a>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('users.destroy', $u) }}" method="POST"
                                  onsubmit="return confirm('Hapus user {{ $u->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:32px" class="text-muted">Belum ada user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection