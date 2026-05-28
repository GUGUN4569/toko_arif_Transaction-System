@extends('layouts.app')

@section('title', 'Detail Nota - Lihat')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Detail Nota</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">ID</th>
                    <td>{{ $detailNota->id }}</td>
                </tr>
                <tr>
                    <th>No. Nota</th>
                    <td>{{ $detailNota->id_nota }}</td>
                </tr>
                <tr>
                    <th>Barang</th>
                    <td>{{ $detailNota->barang->nama_barang ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Banyaknya</th>
                    <td>{{ $detailNota->banyaknya }}</td>
                </tr>
                <tr>
                    <th>Subtotal</th>
                    <td>Rp {{ number_format($detailNota->subtotal_nota, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Dibuat pada</th>
                    <td>{{ $detailNota->created_at ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Diperbarui pada</th>
                    <td>{{ $detailNota->updated_at ?? '—' }}</td>
                </tr>
            </table>

            <div class="d-flex justify-content-start gap-2">
                <a href="{{ route('detail-nota.edit', $detailNota->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('detail-nota.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection