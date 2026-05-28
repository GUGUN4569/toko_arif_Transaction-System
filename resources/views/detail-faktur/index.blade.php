@extends('layouts.app')

@section('title', 'Detail Faktur')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Detail Faktur</h1>
        <a href="{{ route('detail-faktur.create') }}" class="btn btn-primary">+ Tambah Detail Faktur</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>No. Faktur</th>
                    <th>Barang</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details as $detail)
                <tr>
                    <td>{{ $detail->id }}</td>
                    <td>{{ $detail->id_faktur }}</td>
                    <td>{{ $detail->barang->nama_barang ?? '—' }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->subtotal_faktur, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <a href="{{ route('detail-faktur.show', $detail->id) }}" class="btn btn-sm btn-info">Show</a>
                        <a href="{{ route('detail-faktur.edit', $detail->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('detail-faktur.destroy', $detail->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data detail faktur.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $details->links() }}
    </div>
</div>
@endsection