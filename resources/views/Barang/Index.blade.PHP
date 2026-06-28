@extends('layouts.app')
@section('title', 'Data Barang')
 
@section('topbar-actions')
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
                    <td>{{ $b->satuan ?? '-' }}</td>
                    <td>
                        <div class="flex gap-2">
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