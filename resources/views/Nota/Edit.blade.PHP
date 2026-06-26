@extends('layouts.app')
@section('title', 'Edit Nota')
 
@section('content')
<form action="{{ route('nota.update', $nota) }}" method="POST" id="nota-form">
@csrf @method('PUT')
 
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">
 
    <div>
        <div class="card mb-4">
            <div class="card-header">
                <span class="card-title">Edit Nota — <span class="badge badge-green font-mono">{{ $nota->id_nota }}</span></span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Tanggal *</label>
                        <input type="date" name="tanggal_nota" value="{{ old('tanggal_nota', $nota->tanggal_nota) }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Tanda Terima</label>
                        <input type="text" name="tanda_terima" value="{{ old('tanda_terima', $nota->tanda_terima) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Customer</label>
                        <select name="id_customer">
                            <option value="">— Pilih Customer —</option>
                            @foreach($customers as $c)
                            <option value="{{ $c->id_customer }}" {{ (old('id_customer', $nota->id_customer) == $c->id_customer) ? 'selected' : '' }}>
                                {{ $c->nama_customer }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pegawai</label>
                        <select name="id_pegawai">
                            <option value="">— Pilih Pegawai —</option>
                            @foreach($pegawais as $p)
                            <option value="{{ $p->id_pegawai }}" {{ (old('id_pegawai', $nota->id_pegawai) == $p->id_pegawai) ? 'selected' : '' }}>
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
                <div style="display:grid;grid-template-columns:1fr 90px 140px;gap:8px;margin-bottom:8px;padding:0 2px">
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Barang</span>
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Qty</span>
                    <span style="font-size:11px;color:var(--muted);text-transform:uppercase">Subtotal</span>
                </div>
                <div id="items-container">
                    @foreach($nota->detailNota as $i => $d)
                    <div class="item-row" id="row-{{ $i }}" style="grid-template-columns:1fr 90px 140px">
                        {{-- Nama barang ditampilkan, id dikirim tersembunyi --}}
                        <input type="text" value="{{ $d->barang->nama_barang ?? $d->id_barang }}" readonly
                               style="opacity:0.8;cursor:default;background:var(--bg3)">
                        <input type="hidden" name="items[{{ $i }}][id_barang]" value="{{ $d->id_barang }}">
                        <input type="number" name="items[{{ $i }}][banyaknya]" min="1" value="{{ $d->banyaknya }}"
                               onchange="updateSubtotal({{ $i }}, {{ $d->barang->harga ?? 0 }}, {{ $d->barang->disc ?? 0 }})"
                               class="qty-input" id="qty-{{ $i }}">
                        <input type="number" name="items[{{ $i }}][subtotal_nota]" min="0" value="{{ $d->subtotal_nota }}"
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
                <span class="text-muted">Total Bayar</span>
                <span id="grand-total" style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--green)">
                    Rp {{ number_format($nota->total_jumlah_nota, 0, ',', '.') }}
                </span>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Perbarui Nota</button>
            <a href="{{ route('nota.show', $nota) }}" class="btn btn-secondary" style="width:100%;justify-content:center;margin-top:8px">Batal</a>
        </div>
    </div>
</div>
</form>
 
<script>
function updateSubtotal(i, harga, disc) {
    const qty = parseFloat(document.getElementById('qty-' + i).value) || 0;
    document.getElementById('sub-' + i).value = (harga * qty * (1 - disc / 100)).toFixed(2);
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