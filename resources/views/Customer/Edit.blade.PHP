@extends('layouts.app')
@section('title', 'Edit Customer')
 
@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header">
        <span class="card-title">Edit Customer</span>
        <a href="{{ route('customer.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('customer.update', $customer) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>ID Customer</label>
                <input type="text" value="{{ $customer->id_customer }}" disabled style="opacity:0.5">
            </div>
            <div class="form-group">
                <label>Nama Customer *</label>
                <input type="text" name="nama_customer" value="{{ old('nama_customer', $customer->nama_customer) }}"
                       class="{{ $errors->has('nama_customer') ? 'is-invalid' : '' }}">
                @error('nama_customer') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Kontak</label>
                <input type="text" name="kontak" value="{{ old('kontak', $customer->kontak) }}" maxlength="50">
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('customer.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection