@extends('layouts.app')
@section('title', 'Faktur Pembelian')
 
@section('topbar-actions')
    <a href="{{ route('faktur.create') }}" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Buat Faktur
    </a>
@endsection
 
@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID Faktur</th>
                    <th>No. Faktur</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Pegawai</th>
                    <th class="text-right">Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fakturs as $f)
                <tr>
                    <td><span class="badge badge-blue font-mono">{{ $f->id_faktur }}</span></td>
                    <td><span class="badge badge-gold font-mono">{{ $f->no_faktur }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($f->tanggal_faktur)->format('d M Y') }}</td>
                    <td>{{ $f->supplier->nama_supplier ?? '-' }}</td>
                    <td>{{ $f->pegawai->nama_pegawai ?? '-' }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($f->total_jumlah_faktur, 0, ',', '.') }}</strong></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('faktur.show', $f) }}" class="btn btn-secondary btn-sm">Detail</a>
                            <a href="{{ route('faktur.edit', $f) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <!-- Tombol Upload -->
                            <button onclick="openUpload('{{ $f->id_faktur }}')"
                                class="btn btn-sm"
                                style="background:rgba(59,130,246,0.12);color:#60a5fa;border:1px solid rgba(59,130,246,0.25);">
                                Upload
                            </button>
                            @if($f->file_faktur)
                                <span class="badge badge-green" title="File sudah diupload">✓</span>
                            @endif
                            <form action="{{ route('faktur.destroy', $f) }}" method="POST" onsubmit="return confirm('Hapus faktur ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:32px;" class="text-muted">Belum ada faktur pembelian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
 
@push('scripts')
<!-- Modal Upload Faktur -->
<div id="modal-upload" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;">
    <div style="background:var(--bg2);border:1px solid var(--border);border-radius:16px;padding:28px;width:90%;max-width:420px;box-shadow:0 24px 48px rgba(0,0,0,0.4);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
            <h3 style="font-size:15px;font-weight:700;">Upload File Faktur</h3>
            <button onclick="closeUpload()" style="background:none;border:none;color:var(--muted);font-size:20px;cursor:pointer;line-height:1">&times;</button>
        </div>
        <form id="form-upload" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom:16px">
                <label style="display:block;font-size:11px;font-weight:700;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px">
                    File Faktur (JPG, PNG, PDF — maks. 5MB)
                </label>
                <input type="file" name="file_faktur" id="input-file"
                    accept=".jpg,.jpeg,.png,.pdf"
                    style="width:100%;background:var(--bg3);border:1px solid var(--border2);border-radius:8px;padding:9px 13px;color:var(--text);font-size:13px;">
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end">
                <button type="button" onclick="closeUpload()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>
<script>
function openUpload(idFaktur) {
    const modal = document.getElementById('modal-upload');
    document.getElementById('form-upload').action = '/faktur/' + idFaktur + '/upload';
    document.getElementById('input-file').value = '';
    modal.style.display = 'flex';
}
function closeUpload() {
    document.getElementById('modal-upload').style.display = 'none';
}
document.getElementById('modal-upload').addEventListener('click', function(e) {
    if (e.target === this) closeUpload();
});
</script>
@endpush