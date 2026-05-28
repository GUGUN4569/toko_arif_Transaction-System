@extends('layouts.app')

@section('title', 'Detail Faktur - Lihat')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Detail Detail Faktur</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">ID</th>
                    <td>{{ $detailFaktur->id }}</td>
                </tr>
                <tr>
                    <th>No. Faktur</th>
                    <td>{{ $detailFaktur->id_faktur }}</td>
                </tr>
                <tr>
                    <th>Barang</th>
                    <td>{{ $detailFaktur->barang->nama_barang ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Quantity</th>
                    <td>{{ $detailFaktur->quantity }}</td>
                </tr>
                <tr>
                    <th>Subtotal</th>
                    <td>Rp {{ number_format($detailFaktur->subtotal_faktur, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Dibuat pada</th>
                    <td>{{ $detailFaktur->created_at ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Diperbarui pada</th>
                    <td>{{ $detailFaktur->updated_at ?? '—' }}</td>
                </tr>
            </table>

            <div class="d-flex justify-content-start gap-2">
                <a href="{{ route('detail-faktur.edit', $detailFaktur->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('detail-faktur.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection