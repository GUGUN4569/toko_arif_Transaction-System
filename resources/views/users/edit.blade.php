@extends('layouts.app')
@section('title', 'Edit User')
 
@section('content')
<div style="max-width:480px">
    <div class="card">
        <div class="card-header"><span class="card-title">Edit User</span></div>
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf @method('PUT')
 
                <div class="form-group">
                    <label>Nama *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
 
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
 
                <div class="form-group">
                    <label>Role *</label>
                    <select name="role" class="{{ $errors->has('role') ? 'is-invalid' : '' }}" required>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kasir" {{ old('role', $user->role) === 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
 
                <div class="form-group">
                    <label>Password Baru <span class="text-muted">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password"
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
 
                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation">
                </div>
 
                <div style="display:flex;gap:8px;margin-top:4px">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection