@extends('layouts.app')
@section('title', 'Edit Faktur')
 
@section('content')
<form action="{{ route('faktur.update', $faktur) }}" method="POST">
@csrf @method('PUT')
 
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">
    <div>
        <div class="card mb-4">
            <div class="card-header">
                <span class="card-title">Edit Faktur — <span class="badge badge-gold font-mono">{{ $faktur->id_faktur }}</span></span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>No. Faktur *</label>
                        <input type="text" name="no_faktur" value="{{ old('no_faktur', $faktur->no_faktur) }}"
                               class="{{ $errors->has('no_faktur') ? 'is-invalid' : '' }}">
                        @error('no_faktur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Tanggal *</label>
                        <input type="date" name="tanggal_faktur" value="{{ old('tanggal_faktur', $faktur->tanggal_faktur) }}" max="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Supplier</label>
                        <input type="text" name="nama_supplier" list="supplier-list" autocomplete="off"
                               value="{{ old('nama_supplier', $faktur->supplier->nama_supplier ?? '') }}"
                               placeholder="Pilih atau ketik nama supplier baru"
                               oninput="toggleSupplierBaru(this.value)">
                        <datalist id="supplier-list">
                            @foreach($suppliers as $s)
                            <option value="{{ $s->nama_supplier }}"></option>
                            @endforeach
                        </datalist>
                        <small class="text-muted" style="font-size:11px">Kalau nama belum ada di daftar, supplier baru akan otomatis dibuat.</small>
                    </div>
                </div>
                <div class="form-row" id="supplier-baru-fields" style="display:none">
                    <div class="form-group">
                        <label>Alamat Supplier <span class="text-muted" style="font-weight:400">(supplier baru)</span></label>
                        <input type="text" name="alamat_supplier" value="{{ old('alamat_supplier') }}" placeholder="Alamat supplier">
                    </div>
                    <div class="form-group">
                        <label>No. Telp Supplier <span class="text-muted" style="font-weight:400">(supplier baru)</span></label>
                        <input type="text" name="no_telp_supplier" value="{{ old('no_telp_supplier') }}" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Pegawai</label>
                        <select name="id_pegawai">
                            <option value="">— Pilih Pegawai —</option>
                            @foreach($pegawais as $p)
                            <option value="{{ $p->id_pegawai }}" {{ (old('id_pegawai', $faktur->id_pegawai) == $p->id_pegawai) ? 'selected' : '' }}>
                                {{ $p->nama_pegawai }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
 
        <div class="card">
            <div class="card-header">
                <span class="card-title">Detail Barang</span>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addItem()">+ Tambah Baris</button>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:2fr 1fr 1fr 90px 140px 36px;gap:8px;margin-bottom:8px">
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Nama Barang</span>
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Satuan</span>
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Harga Beli</span>
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Qty</span>
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Subtotal</span>
                    <span></span>
                </div>
                <div id="items-container">
                    @foreach($faktur->detailFaktur as $i => $d)
                    @php
                        // Hitung mundur harga satuan asal untuk form input harga
                        $hargaAsal = $d->quantity > 0 ? ($d->subtotal_faktur / $d->quantity) : 0;
                    @endphp
                    <div class="item-row" id="row-{{ $i }}" style="display:grid;grid-template-columns:2fr 1fr 1fr 90px 140px 36px;gap:8px;margin-bottom:8px">
                        <input type="text" name="items[{{ $i }}][nama_barang]" value="{{ $d->barang->nama_barang ?? '' }}" placeholder="Nama Barang" class="form-control" required>
                        <input type="text" name="items[{{ $i }}][satuan]" value="{{ $d->barang->satuan ?? '' }}" placeholder="Pcs/Dus/Kg" class="form-control" required>
                        <input type="number" name="items[{{ $i }}][harga_satuan]" min="0" value="{{ $hargaAsal }}" oninput="updateSubtotal({{ $i }})" class="form-control harga-input">
                        <input type="number" name="items[{{ $i }}][quantity]" min="1" value="{{ $d->quantity }}" oninput="updateSubtotal({{ $i }})" class="form-control qty-input" id="qty-{{ $i }}">
                        <input type="number" name="items[{{ $i }}][subtotal_faktur]" min="0" value="{{ $d->subtotal_faktur }}" readonly style="opacity:0.7" class="form-control subtotal-input" id="sub-{{ $i }}">
                        <button type="button" onclick="removeItem(this)" style="background:rgba(239,68,68,0.15);color:var(--red);border:1px solid rgba(239,68,68,0.3);border-radius:6px;padding:6px 10px;cursor:pointer;font-size:14px">×</button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
 
    <div class="card" style="position:sticky;top:76px">
        <div class="card-header"><span class="card-title">Ringkasan</span></div>
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border)">
                <span class="text-muted">Total Pembelian</span>
                <span id="grand-total" style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--accent)">
                    Rp {{ number_format($faktur->total_jumlah_faktur, 0, ',', '.') }}
                </span>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Perbarui Faktur</button>
            <a href="{{ route('faktur.show', $faktur) }}" class="btn btn-secondary" style="width:100%;justify-content:center;margin-top:8px">Batal</a>
        </div>
    </div>
</div>
</form>
 
<script>
// Menghitung index awal baris berdasarkan item yang sudah ada dari database
let rowCount = {{ $faktur->detailFaktur->count() }};
const namaSupplierTersedia = @json($suppliers->pluck('nama_supplier'));

function toggleSupplierBaru(value) {
    const sudahAda = namaSupplierTersedia.some(n => n.toLowerCase() === value.trim().toLowerCase());
    document.getElementById('supplier-baru-fields').style.display = (value.trim() && !sudahAda) ? 'grid' : 'none';
}
toggleSupplierBaru(document.querySelector('input[name="nama_supplier"]').value);
 
function addItem() {
    const i = rowCount++;
    const row = document.createElement('div');
    row.className = 'item-row';
    row.id = `row-${i}`;
    row.style = 'display:grid;grid-template-columns:2fr 1fr 1fr 90px 140px 36px;gap:8px;margin-bottom:8px';
    row.innerHTML = `
        <input type="text" name="items[${i}][nama_barang]" placeholder="Nama Barang" class="form-control" required>
        <input type="text" name="items[${i}][satuan]" placeholder="Pcs/Dus/Kg" class="form-control" required>
        <input type="number" name="items[${i}][harga_satuan]" min="0" value="0" oninput="updateSubtotal(${i})" class="form-control harga-input">
        <input type="number" name="items[${i}][quantity]" min="1" value="1" oninput="updateSubtotal(${i})" class="form-control qty-input">
        <input type="number" name="items[${i}][subtotal_faktur]" min="0" value="0" readonly style="opacity:0.7" class="form-control subtotal-input">
        <button type="button" onclick="removeItem(this)" style="background:rgba(239,68,68,0.15);color:var(--red);border:1px solid rgba(239,68,68,0.3);border-radius:6px;padding:6px 10px;cursor:pointer;font-size:14px">×</button>
    `;
    document.getElementById('items-container').appendChild(row);
}
 
function removeItem(btn) {
    if (document.querySelectorAll('#items-container .item-row').length <= 1) return;
    btn.closest('.item-row').remove();
    updateGrandTotal();
}
 
function updateSubtotal(i) {
    const row = document.getElementById(`row-${i}`);
    if (!row) return;
    const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    row.querySelector('.subtotal-input').value = (harga * qty).toFixed(2);
    updateGrandTotal();
}
 
function updateGrandTotal() {
    let total = 0;
    document.querySelectorAll('#items-container .subtotal-input').forEach(el => total += parseFloat(el.value) || 0);
    document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID', {maximumFractionDigits:0});
}
</script>
@endsection