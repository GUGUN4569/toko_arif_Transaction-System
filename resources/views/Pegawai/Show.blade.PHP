@extends('layouts.app')
@section('title', 'Detail Pegawai')
 
@section('content')
<div class="card" style="max-width:400px">
    <div class="card-header">
        <span class="card-title">Detail Pegawai</span>
        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <table style="width:100%">
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px;width:130px">ID</td><td><span class="badge badge-blue">#{{ $pegawai->id_pegawai }}</span></td></tr>
            <tr><td style="padding:10px 0;color:var(--muted);font-size:13px">Nama</td><td><strong>{{ $pegawai->nama_pegawai }}</strong></td></tr>
        </table>
    </div>
</div>
@endsection