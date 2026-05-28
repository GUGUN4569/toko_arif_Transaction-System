@extends('layouts.app')

@section('title', 'Tambah Detail Faktur')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tambah Detail Faktur</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('detail-faktur.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="id_faktur" class="form-label">No. Faktur</label>
                    <select name="id_faktur" id="id_faktur" class="form-select @error('id_faktur') is-invalid @enderror" required>
                        <option value="">-- Pilih Faktur --</option>
                        @foreach ($fakturs as $faktur)
                            <option value="{{ $faktur->id_faktur }}" {{ old('id_faktur') == $faktur->id_faktur ? 'selected' : '' }}>
                                {{ $faktur->id_faktur }} (Total: {{ number_format($faktur->total_jumlah_faktur, 0) }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_faktur')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="id_barang" class="form-label">Barang</label>
                    <select name="id_barang" id="id_barang" class="form-select @error('id_barang') is-invalid @enderror" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id_barang }}" {{ old('id_barang') == $barang->id_barang ? 'selected' : '' }}>
                                {{ $barang->nama_barang }} (Stok: {{ $barang->stok ?? '?' }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" min="1" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="subtotal_faktur" class="form-label">Subtotal (Rp)</label>
                    <input type="number" step="0.01" name="subtotal_faktur" id="subtotal_faktur" class="form-control @error('subtotal_faktur') is-invalid @enderror" value="{{ old('subtotal_faktur') }}" required>
                    @error('subtotal_faktur')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('detail-faktur.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection