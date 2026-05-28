@extends('layouts.app')
@section('title', 'Edit Pegawai')
 
@section('content')
<div class="card" style="max-width:480px">
    <div class="card-header">
        <span class="card-title">Edit Pegawai</span>
        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('pegawai.update', $pegawai) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nama Pegawai *</label>
                <input type="text" name="nama_pegawai" value="{{ old('nama_pegawai', $pegawai->nama_pegawai) }}"
                       class="{{ $errors->has('nama_pegawai') ? 'is-invalid' : '' }}">
                @error('nama_pegawai') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection