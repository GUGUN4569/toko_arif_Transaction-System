@extends('layouts.app')
@section('title', 'Tambah Supplier')
 
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">Form Tambah Supplier</span>
        <a href="{{ route('supplier.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('supplier.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>ID Supplier *</label>
                    <input type="text" name="id_supplier" value="{{ old('id_supplier') }}"
                           class="{{ $errors->has('id_supplier') ? 'is-invalid' : '' }}"
                           placeholder="Contoh: SUP001" maxlength="10">
                    @error('id_supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telp_supplier" value="{{ old('no_telp_supplier') }}" placeholder="08xxxxxxxxxx" maxlength="20">
                </div>
            </div>
            <div class="form-group">
                <label>Nama Supplier *</label>
                <input type="text" name="nama_supplier" value="{{ old('nama_supplier') }}"
                       class="{{ $errors->has('nama_supplier') ? 'is-invalid' : '' }}"
                       placeholder="Nama perusahaan / distributor">
                @error('nama_supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat_supplier" rows="3" placeholder="Alamat lengkap supplier">{{ old('alamat_supplier') }}</textarea>
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection