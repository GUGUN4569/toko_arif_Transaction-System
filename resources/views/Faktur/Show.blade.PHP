@extends('layouts.app')
@section('title', 'Detail Faktur')
 
@section('content')
<div style="max-width:700px">
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-title">Faktur — <span class="badge badge-gold font-mono">{{ $faktur->no_faktur }}</span></span>
            <div class="flex gap-2">
                @if($faktur->file_faktur)
                    <a href="{{ Storage::url($faktur->file_faktur) }}" target="_blank" class="btn btn-primary btn-sm">
                        📎 Lihat File Faktur
                    </a>
                    <form action="{{ route('faktur.delete-file', $faktur->id_faktur) }}" method="POST"
                          onsubmit="return confirm('Hapus file faktur ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus File</button>
                    </form>
                @else
                    <span class="btn btn-sm" style="background:var(--bg3);color:var(--muted);border:1px solid var(--border);cursor:default;">
                        📎 Belum Ada File
                    </span>
                @endif
                <a href="{{ route('faktur.edit', $faktur->id_faktur) }}" class="btn btn-secondary btn-sm">Edit</a>
                <form action="{{ route('faktur.destroy', $faktur->id_faktur) }}" method="POST"
                      onsubmit="return confirm('Hapus faktur {{ $faktur->id_faktur }} beserta semua detailnya?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                </form>
                <a href="{{ route('faktur.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <div class="text-muted" style="font-size:12px;margin-bottom:4px">ID FAKTUR</div>
                    <div><span class="badge badge-blue font-mono">{{ $faktur->id_faktur }}</span></div>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;margin-bottom:4px">TANGGAL</div>
                    <div>{{ \Carbon\Carbon::parse($faktur->tanggal_faktur)->format('d F Y') }}</div>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;margin-bottom:4px">SUPPLIER</div>
                    <div>{{ $faktur->supplier->nama_supplier ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;margin-bottom:4px">PEGAWAI</div>
                    <div>{{ $faktur->pegawai->nama_pegawai ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>
 
    <div class="card">
        <div class="card-header">
            <span class="card-title">Item Pembelian</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="openTambah()">+ Tambah Item</button>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Barang</th>
                        <th>Quantity</th>
                        <th class="text-right">Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faktur->detailFaktur as $i => $d)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td>{{ $d->barang->nama_barang ?? $d->id_barang }}</td>
                        <td>{{ $d->quantity }} {{ $d->barang->satuan ?? '' }}</td>
                        <td class="text-right">Rp {{ number_format($d->subtotal_faktur, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('detail-faktur.destroy', $d->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus item ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="border-top:2px solid var(--border2)">
                        <td colspan="3" style="padding:14px 16px;text-align:right;font-weight:700;text-transform:uppercase;font-size:12px;color:var(--muted)">Total</td>
                        <td style="padding:14px 16px;text-align:right;font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--accent)">
                            Rp {{ number_format($faktur->total_jumlah_faktur, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
 
{{-- Modal Tambah Item --}}
<div id="modal-tambah" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;">
    <div style="background:var(--bg2);border:1px solid var(--border);border-radius:16px;padding:28px;width:90%;max-width:440px;box-shadow:0 24px 48px rgba(0,0,0,0.4);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
            <h3 style="font-size:15px;font-weight:700;">+ Tambah Item ke Faktur</h3>
            <button onclick="closeTambah()" style="background:none;border:none;color:var(--muted);font-size:20px;cursor:pointer;">&times;</button>
        </div>
        <form action="{{ route('detail-faktur.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_faktur" value="{{ $faktur->id_faktur }}">
 
            <div class="form-group">
                <label>Barang *</label>
                <select name="id_barang" id="modal-barang" onchange="hitungSubtotal()" required>
                    <option value="">— Pilih Barang —</option>
                    @foreach($barangs as $b)
                    <option value="{{ $b->id_barang }}" data-harga="{{ $b->harga }}">
                        {{ $b->nama_barang }} (Rp {{ number_format($b->harga, 0, ',', '.') }})
                    </option>
                    @endforeach
                </select>
            </div>
 
            <div class="form-group">
                <label>Quantity *</label>
                <input type="number" name="quantity" id="modal-qty" min="1" value="1" onchange="hitungSubtotal()" required>
            </div>
 
            <div class="form-group">
                <label>Subtotal</label>
                <input type="number" name="subtotal_faktur" id="modal-subtotal" min="0" value="0" readonly style="opacity:0.7">
            </div>
 
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:4px">
                <button type="button" onclick="closeTambah()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Item</button>
            </div>
        </form>
    </div>
</div>
 
<script>
function openTambah() {
    document.getElementById('modal-tambah').style.display = 'flex';
}
function closeTambah() {
    document.getElementById('modal-tambah').style.display = 'none';
}
function hitungSubtotal() {
    const sel = document.getElementById('modal-barang');
    const opt = sel.selectedOptions[0];
    const harga = parseFloat(opt?.dataset?.harga || 0);
    const qty   = parseFloat(document.getElementById('modal-qty').value) || 0;
    document.getElementById('modal-subtotal').value = (harga * qty).toFixed(2);
}
document.getElementById('modal-tambah').addEventListener('click', function(e) {
    if (e.target === this) closeTambah();
});
</script>
@endsection