@extends('layouts.app')
@section('title', 'Edit Supplier')
 
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">Edit Supplier</span>
        <a href="{{ route('supplier.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('supplier.update', $supplier) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>ID Supplier</label>
                <input type="text" value="{{ $supplier->id_supplier }}" disabled style="opacity:0.5">
            </div>
            <div class="form-group">
                <label>Nama Supplier *</label>
                <input type="text" name="nama_supplier" value="{{ old('nama_supplier', $supplier->nama_supplier) }}"
                       class="{{ $errors->has('nama_supplier') ? 'is-invalid' : '' }}">
                @error('nama_supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_telp_supplier" value="{{ old('no_telp_supplier', $supplier->no_telp_supplier) }}" maxlength="20">
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat_supplier" rows="3">{{ old('alamat_supplier', $supplier->alamat_supplier) }}</textarea>
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection