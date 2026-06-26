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
                        <select name="id_supplier">
                            <option value="">— Pilih Supplier —</option>
                            @foreach($suppliers as $s)
                            <option value="{{ $s->id_supplier }}" {{ (old('id_supplier', $faktur->id_supplier) == $s->id_supplier) ? 'selected' : '' }}>
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
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 90px 140px;gap:8px;margin-bottom:8px">
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Barang</span>
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Qty</span>
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Subtotal</span>
                </div>
                <div id="items-container">
                    @foreach($faktur->detailFaktur as $i => $d)
                    <div class="item-row" id="row-{{ $i }}" style="grid-template-columns:1fr 90px 140px">
                        <input type="text" value="{{ $d->barang->nama_barang ?? $d->id_barang }}" readonly
                               style="opacity:0.8;cursor:default;background:var(--bg3)">
                        <input type="hidden" name="items[{{ $i }}][id_barang]" value="{{ $d->id_barang }}">
                        <input type="number" name="items[{{ $i }}][quantity]" min="1" value="{{ $d->quantity }}"
                               onchange="updateSubtotal({{ $i }}, {{ $d->barang->harga ?? 0 }})"
                               class="qty-input" id="qty-{{ $i }}">
                        <input type="number" name="items[{{ $i }}][subtotal_faktur]" min="0" value="{{ $d->subtotal_faktur }}"
                               readonly style="opacity:0.7" id="sub-{{ $i }}">
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
function updateSubtotal(i, harga) {
    const qty = parseFloat(document.getElementById('qty-' + i).value) || 0;
    document.getElementById('sub-' + i).value = (harga * qty).toFixed(2);
    updateGrandTotal();
}
 
function updateGrandTotal() {
    let total = 0;
    document.querySelectorAll('#items-container input[readonly][type="number"]').forEach(el => {
        total += parseFloat(el.value) || 0;
    });
    document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID', {maximumFractionDigits:0});
}
</script>
@endsection