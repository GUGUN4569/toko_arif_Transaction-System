@extends('layouts.app')
@section('title', 'Detail Nota')
 
@section('content')
<div style="max-width:700px">
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-title">Nota — <span class="badge badge-green font-mono">{{ $nota->id_nota }}</span></span>
            <div class="flex gap-2">
                <a href="{{ route('nota.print', $nota->id_nota) }}" target="_blank" class="btn btn-primary btn-sm">🖨️ Cetak</a>
                <a href="{{ route('nota.edit', $nota->id_nota) }}" class="btn btn-secondary btn-sm">Edit</a>
                <form action="{{ route('nota.destroy', $nota->id_nota) }}" method="POST"
                      onsubmit="return confirm('Hapus nota {{ $nota->id_nota }} beserta semua detailnya?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                </form>
                <a href="{{ route('nota.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <div class="text-muted" style="font-size:12px;margin-bottom:4px">TANGGAL</div>
                    <div>{{ \Carbon\Carbon::parse($nota->tanggal_nota)->format('d F Y') }}</div>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;margin-bottom:4px">CUSTOMER</div>
                    <div>{{ $nota->customer->nama_customer ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;margin-bottom:4px">PEGAWAI</div>
                    <div>{{ $nota->pegawai->nama_pegawai ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;margin-bottom:4px">TANDA TERIMA</div>
                    <div>{{ $nota->tanda_terima ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>
 
    <div class="card">
        <div class="card-header">
            <span class="card-title">Item Penjualan</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="openTambah()">+ Tambah Item</button>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th class="text-right">Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nota->detailNota as $i => $d)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td>{{ $d->barang->nama_barang ?? $d->id_barang }}</td>
                        <td>{{ $d->banyaknya }} {{ $d->barang->satuan ?? '' }}</td>
                        <td class="text-right">Rp {{ number_format($d->subtotal_nota, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('detail-nota.destroy', $d->id) }}" method="POST"
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
                        <td colspan="3" style="padding:14px 16px;text-align:right;font-weight:700;text-transform:uppercase;font-size:12px;color:var(--muted);letter-spacing:.5px">Total</td>
                        <td style="padding:14px 16px;text-align:right;font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--green)">
                            Rp {{ number_format($nota->total_jumlah_nota, 0, ',', '.') }}
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
            <h3 style="font-size:15px;font-weight:700;">+ Tambah Item ke Nota</h3>
            <button onclick="closeTambah()" style="background:none;border:none;color:var(--muted);font-size:20px;cursor:pointer;">&times;</button>
        </div>
        <form action="{{ route('detail-nota.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_nota" value="{{ $nota->id_nota }}">
 
            <div class="form-group">
                <label>Barang *</label>
                <select name="id_barang" id="modal-barang" onchange="hitungSubtotal()" required>
                    <option value="">— Pilih Barang —</option>
                    @foreach($barangs as $b)
                    <option value="{{ $b->id_barang }}" data-harga="{{ $b->harga }}" data-disc="{{ $b->disc ?? 0 }}">
                        {{ $b->nama_barang }} (Rp {{ number_format($b->harga, 0, ',', '.') }})
                    </option>
                    @endforeach
                </select>
            </div>
 
            <div class="form-group">
                <label>Banyaknya *</label>
                <input type="number" name="banyaknya" id="modal-qty" min="1" value="1" onchange="hitungSubtotal()" required>
            </div>
 
            <div class="form-group">
                <label>Subtotal</label>
                <input type="number" name="subtotal_nota" id="modal-subtotal" min="0" value="0" readonly style="opacity:0.7">
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
    const disc  = parseFloat(opt?.dataset?.disc  || 0);
    const qty   = parseFloat(document.getElementById('modal-qty').value) || 0;
    document.getElementById('modal-subtotal').value = (harga * qty * (1 - disc / 100)).toFixed(2);
}
document.getElementById('modal-tambah').addEventListener('click', function(e) {
    if (e.target === this) closeTambah();
});
</script>
@endsection