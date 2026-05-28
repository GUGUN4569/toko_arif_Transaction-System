@extends('layouts.app')
@section('title', 'Tambah Customer')
 
@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header">
        <span class="card-title">Form Tambah Customer</span>
        <a href="{{ route('customer.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('customer.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>ID Customer *</label>
                    <input type="text" name="id_customer" value="{{ old('id_customer') }}"
                           class="{{ $errors->has('id_customer') ? 'is-invalid' : '' }}"
                           placeholder="Contoh: CST001" maxlength="10">
                    @error('id_customer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-group">
                <label>Nama Customer *</label>
                <input type="text" name="nama_customer" value="{{ old('nama_customer') }}"
                       class="{{ $errors->has('nama_customer') ? 'is-invalid' : '' }}"
                       placeholder="Nama lengkap customer">
                @error('nama_customer') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Kontak (No. HP / Email)</label>
                <input type="text" name="kontak" value="{{ old('kontak') }}" placeholder="08xxxxxxxxxx" maxlength="50">
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('customer.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection