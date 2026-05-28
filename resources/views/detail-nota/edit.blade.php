@extends('layouts.app')

@section('title', 'Edit Detail Nota')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Detail Nota</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('detail-nota.update', $detailNota->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id_nota" class="form-label">No. Nota</label>
                    <select name="id_nota" id="id_nota" class="form-select @error('id_nota') is-invalid @enderror" required>
                        <option value="">-- Pilih Nota --</option>
                        @foreach ($notas as $nota)
                            <option value="{{ $nota->id_nota }}" {{ old('id_nota', $detailNota->id_nota) == $nota->id_nota ? 'selected' : '' }}>
                                {{ $nota->id_nota }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_nota')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="id_barang" class="form-label">Barang</label>
                    <select name="id_barang" id="id_barang" class="form-select @error('id_barang') is-invalid @enderror" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id_barang }}" {{ old('id_barang', $detailNota->id_barang) == $barang->id_barang ? 'selected' : '' }}>
                                {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="banyaknya" class="form-label">Banyaknya</label>
                    <input type="number" name="banyaknya" id="banyaknya" class="form-control @error('banyaknya') is-invalid @enderror" value="{{ old('banyaknya', $detailNota->banyaknya) }}" min="1" required>
                    @error('banyaknya')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="subtotal_nota" class="form-label">Subtotal (Rp)</label>
                    <input type="number" step="0.01" name="subtotal_nota" id="subtotal_nota" class="form-control @error('subtotal_nota') is-invalid @enderror" value="{{ old('subtotal_nota', $detailNota->subtotal_nota) }}" required>
                    @error('subtotal_nota')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('detail-nota.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection