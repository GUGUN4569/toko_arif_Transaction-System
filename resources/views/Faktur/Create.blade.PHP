@extends('layouts.app')
@section('title', 'Buat Faktur Pembelian')
 
@section('content')
<form action="{{ route('faktur.store') }}" method="POST">
@csrf
 
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">
    <div>
        <div class="card mb-4">
            <div class="card-header"><span class="card-title">Informasi Faktur</span></div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>No. Faktur *</label>
                        <input type="text" name="no_faktur" value="{{ old('no_faktur') }}"
                               class="{{ $errors->has('no_faktur') ? 'is-invalid' : '' }}"
                               placeholder="No referensi faktur supplier">
                        @error('no_faktur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Tanggal *</label>
                        <input type="date" name="tanggal_faktur" value="{{ old('tanggal_faktur', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Supplier</label>
                        <select name="id_supplier">
                            <option value="">— Pilih Supplier —</option>
                            @foreach($suppliers as $s)
                            <option value="{{ $s->id_supplier }}" {{ old('id_supplier') == $s->id_supplier ? 'selected' : '' }}>
                                {{ $s->nama_supplier }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pegawai</label>
                        <select name="id_pegawai">
                            <option value="">— Pilih Pegawai —</option>
                            @foreach($pegawais as $p)
                            <option value="{{ $p->id_pegawai }}" {{ old('id_pegawai') == $p->id_pegawai ? 'selected' : '' }}>
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
                <div style="display:grid;grid-template-columns:1fr 90px 140px 36px;gap:8px;margin-bottom:8px">
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Barang</span>
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Qty</span>
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Subtotal</span>
                    <span></span>
                </div>
                <div id="items-container">
                    <div class="item-row" id="row-0">
                        <select name="items[0][id_barang]" onchange="updateSubtotal(0)" class="barang-select">
                            <option value="">— Pilih Barang —</option>
                            @foreach($barangs as $b)
                            <option value="{{ $b->id_barang }}" data-harga="{{ $b->harga }}">
                                {{ $b->nama_barang }} (Rp {{ number_format($b->harga, 0, ',', '.') }})
                            </option>
                            @endforeach
                        </select>
                        <input type="number" name="items[0][quantity]" min="1" value="1" onchange="updateSubtotal(0)" class="qty-input">
                        <input type="number" name="items[0][subtotal_faktur]" min="0" value="0" readonly style="opacity:0.7">
                        <button type="button" onclick="removeItem(this)" style="background:rgba(239,68,68,0.15);color:var(--red);border:1px solid rgba(239,68,68,0.3);border-radius:6px;padding:6px 10px;cursor:pointer;font-size:14px">×</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <div class="card" style="position:sticky;top:76px">
        <div class="card-header"><span class="card-title">Ringkasan</span></div>
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border)">
                <span class="text-muted">Total Pembelian</span>
                <span id="grand-total" style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--accent)">Rp 0</span>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Simpan Faktur</button>
            <a href="{{ route('faktur.index') }}" class="btn btn-secondary" style="width:100%;justify-content:center;margin-top:8px">Batal</a>
        </div>
    </div>
</div>
</form>
 
<script>
let rowCount = 1;
const barangs = @json($barangs->keyBy('id_barang'));
 
function addItem() {
    const i = rowCount++;
    const options = Object.entries(barangs).map(([id, b]) =>
        `<option value="${id}" data-harga="${b.harga}">${b.nama_barang} (Rp ${Number(b.harga).toLocaleString('id-ID')})</option>`
    ).join('');
    const row = document.createElement('div');
    row.className = 'item-row';
    row.id = `row-${i}`;
    row.innerHTML = `
        <select name="items[${i}][id_barang]" onchange="updateSubtotal(${i})" class="barang-select">
            <option value="">— Pilih Barang —</option>${options}
        </select>
        <input type="number" name="items[${i}][quantity]" min="1" value="1" onchange="updateSubtotal(${i})" class="qty-input">
        <input type="number" name="items[${i}][subtotal_faktur]" min="0" value="0" readonly style="opacity:0.7">
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
    const opt = row.querySelector('.barang-select').selectedOptions[0];
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const harga = parseFloat(opt?.dataset?.harga || 0);
    row.querySelectorAll('input[type="number"]')[1].value = (harga * qty).toFixed(2);
    updateGrandTotal();
}
 
function updateGrandTotal() {
    let total = 0;
    document.querySelectorAll('#items-container input[readonly]').forEach(el => total += parseFloat(el.value) || 0);
    document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID', {maximumFractionDigits:0});
}
</script>
@endsection