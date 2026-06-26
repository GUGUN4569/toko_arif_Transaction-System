@extends('layouts.app')
@section('title', 'Edit Barang')
 
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">Edit Barang — <span class="badge badge-blue font-mono">{{ $barang->id_barang }}</span></span>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('barang.update', $barang) }}" method="POST">
            @csrf @method('PUT')
 
            <div class="form-group">
                <label>ID Barang</label>
                <input type="text" value="{{ $barang->id_barang }}" disabled style="opacity:0.5;cursor:not-allowed">
            </div>
 
            <div class="form-group">
                <label>Nama Barang *</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"
                       class="{{ $errors->has('nama_barang') ? 'is-invalid' : '' }}">
                @error('nama_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
 
            <div class="form-row">
                <div class="form-group">
                    <label>Harga *</label>
                    <input type="number" name="harga" value="{{ old('harga', $barang->harga) }}"
                           class="{{ $errors->has('harga') ? 'is-invalid' : '' }}" min="0" step="100">
                    @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan) }}" maxlength="20">
                </div>
            </div>
 
            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection