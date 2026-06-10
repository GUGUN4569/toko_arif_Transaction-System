@extends('layouts.app')
@section('title', 'Tambah User')
 
@section('content')
<div style="max-width:480px">
    <div class="card">
        <div class="card-header"><span class="card-title">Tambah User Baru</span></div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
 
                <div class="form-group">
                    <label>Nama *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
 
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
 
                <div class="form-group">
                    <label>Role *</label>
                    <select name="role" class="{{ $errors->has('role') ? 'is-invalid' : '' }}" required>
                        <option value="">— Pilih Role —</option>
                        <option value="admin"  {{ old('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
                        <option value="kasir"  {{ old('role') === 'kasir'  ? 'selected' : '' }}>Kasir</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div style="font-size:12px;color:var(--muted);margin-top:5px">
                        <strong>Admin:</strong> akses penuh &nbsp;|&nbsp;
                        <strong>Kasir:</strong> hanya input Nota Penjualan
                    </div>
                </div>
 
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password"
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
 
                <div class="form-group">
                    <label>Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" required>
                </div>
 
                <div style="display:flex;gap:8px;margin-top:4px">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection