@extends('layouts.app')
@section('title', 'Tambah Barang')
 
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">Form Tambah Barang</span>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>ID Barang *</label>
                    <input type="text" name="id_barang" value="{{ old('id_barang') }}"
                           class="{{ $errors->has('id_barang') ? 'is-invalid' : '' }}"
                           placeholder="Contoh: BRG001" maxlength="10">
                    @error('id_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="satuan" value="{{ old('satuan') }}" placeholder="pcs, kg, lusin..." maxlength="20">
                </div>
            </div>
 
            <div class="form-group">
                <label>Nama Barang *</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                       class="{{ $errors->has('nama_barang') ? 'is-invalid' : '' }}"
                       placeholder="Nama lengkap barang">
                @error('nama_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
 
            <div class="form-row">
                <div class="form-group">
                    <label>Harga *</label>
                    <input type="number" name="harga" value="{{ old('harga') }}"
                           class="{{ $errors->has('harga') ? 'is-invalid' : '' }}"
                           placeholder="0" min="0" step="100">
                    @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
 
            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Simpan Barang</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection