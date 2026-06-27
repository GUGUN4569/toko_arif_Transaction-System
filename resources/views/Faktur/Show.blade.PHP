@extends('layouts.app')
@section('title', 'Detail Faktur')

@section('content')
<div style="max-width:700px">

    {{-- Flash message --}}
    @if(session('success'))
    <div style="background:var(--green-soft,#e6f9f0);border:1px solid var(--green);border-radius:10px;padding:12px 16px;margin-bottom:16px;color:var(--green);font-size:13px;font-weight:600;">
        ✅ {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div style="background:rgba(239,68,68,0.15);border:1px solid var(--red);border-radius:10px;padding:12px 16px;margin-bottom:16px;color:var(--red);font-size:13px;font-weight:600;">
        ❌ {{ session('error') }}
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <span class="card-title">Faktur — <span class="badge badge-gold font-mono">{{ $faktur->no_faktur }}</span></span>
            <div class="flex gap-2">
                @if($faktur->isLocked())
                    <span class="badge" style="background:rgba(107,114,128,0.15);color:#9ca3af;border:1px solid rgba(107,114,128,0.3)">🔒 Terkunci (&gt;14 hari)</span>
                @endif
                @if($faktur->file_faktur)
                    <a href="{{ Storage::url($faktur->file_faktur) }}" target="_blank" class="btn btn-primary btn-sm">
                        Lihat File Faktur
                    </a>
                    @unless($faktur->isLocked())
                    <form action="{{ route('faktur.delete-file', $faktur) }}" method="POST"
                          onsubmit="return confirm('Hapus file faktur ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus File</button>
                    </form>
                    @endunless
                @else
                    <span class="btn btn-sm" style="background:var(--bg3);color:var(--muted);border:1px solid var(--border);cursor:default;">
                        Belum Ada File
                    </span>
                @endif
                @unless($faktur->isLocked())
                <form action="{{ route('faktur.destroy', $faktur) }}" method="POST"
                      onsubmit="return confirm('Hapus faktur {{ $faktur->id_faktur }} beserta semua detailnya?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
                @endunless
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
            @unless($faktur->isLocked())
            <button type="button" class="btn btn-primary btn-sm" onclick="openTambah()">+ Tambah Item</button>
            @endunless
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Barang</th>
                        <th>Quantity</th>
                        <th class="text-right">Subtotal</th>
                        @unless($faktur->isLocked())
                        <th>Aksi</th>
                        @endunless
                    </tr>
                </thead>
                <tbody>
                    @foreach($faktur->detailFaktur as $i => $d)
                    @php
                        $hargaSatuanItem = $d->quantity > 0 ? ($d->subtotal_faktur / $d->quantity) : 0;
                        $namaBarangAsli = $d->barang->nama_barang ?? $d->id_barang;
                        $satuanBarangAsli = $d->barang->satuan ?? '';
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td>{{ $namaBarangAsli }}</td>
                        <td>{{ $d->quantity }} {{ $satuanBarangAsli }}</td>
                        <td class="text-right">Rp {{ number_format($d->subtotal_faktur, 0, ',', '.') }}</td>
                        @unless($faktur->isLocked())
                        <td>
                            <div style="display:flex;gap:6px;align-items:center">
                                {{-- Tombol Edit membawa data string teks nama barang dan satuan secara utuh --}}
                                <button type="button" class="btn btn-secondary btn-sm"
                                    onclick="openEdit(
                                        '{{ $d->id }}',
                                        '{{ addslashes($namaBarangAsli) }}',
                                        '{{ addslashes($satuanBarangAsli) }}',
                                        {{ $hargaSatuanItem }},
                                        {{ $d->quantity }},
                                        {{ $d->subtotal_faktur }}
                                    )">Edit</button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('detail-faktur.destroy', $d->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus item ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                        @endunless
                    </tr>
                    @endforeach

                    @if($faktur->detailFaktur->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px 16px;color:var(--muted)">
                            Belum ada item. Klik "+ Tambah Item" untuk menambahkan.
                        </td>
                    </tr>
                    @endif
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

{{-- ============================================================ --}}
{{-- Modal Tambah Item                                            --}}
{{-- ============================================================ --}}
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
                <label>Nama Barang *</label>
                <input type="text" name="nama_barang" id="modal-nama-barang" placeholder="Ketik nama barang..." required>
            </div>

            <div class="form-group">
                <label>Satuan *</label>
                <input type="text" name="satuan" id="modal-satuan" placeholder="Contoh: Pcs, Dus, Kg" required>
            </div>

            <div class="form-group">
                <label>Harga Satuan *</label>
                <input type="number" name="harga_satuan" id="modal-harga" min="0" value="0" oninput="hitungSubtotal()" required>
            </div>

            <div class="form-group">
                <label>Quantity *</label>
                <input type="number" name="quantity" id="modal-qty" min="1" value="1" oninput="hitungSubtotal()" required>
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

{{-- ============================================================ --}}
{{-- Modal Edit Item                                              --}}
{{-- ============================================================ --}}
<div id="modal-edit" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;">
    <div style="background:var(--bg2);border:1px solid var(--border);border-radius:16px;padding:28px;width:90%;max-width:440px;box-shadow:0 24px 48px rgba(0,0,0,0.4);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
            <h3 style="font-size:15px;font-weight:700;">Edit Item</h3>
            <button onclick="closeEdit()" style="background:none;border:none;color:var(--muted);font-size:20px;cursor:pointer;">&times;</button>
        </div>
        <form id="form-edit" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_faktur" value="{{ $faktur->id_faktur }}">

            <div class="form-group">
                <label>Nama Barang *</label>
                <input type="text" name="nama_barang" id="edit-nama-barang" placeholder="Nama barang..." required>
            </div>

            <div class="form-group">
                <label>Satuan *</label>
                <input type="text" name="satuan" id="edit-satuan" placeholder="Contoh: Pcs, Dus, Kg" required>
            </div>

            <div class="form-group">
                <label>Harga Satuan *</label>
                <input type="number" name="harga_satuan" id="edit-harga" min="0" value="0" oninput="hitungSubtotalEdit()" required>
            </div>

            <div class="form-group">
                <label>Quantity *</label>
                <input type="number" name="quantity" id="edit-qty" min="1" value="1" oninput="hitungSubtotalEdit()" required>
            </div>

            <div class="form-group">
                <label>Subtotal</label>
                <input type="number" name="subtotal_faktur" id="edit-subtotal" min="0" value="0" readonly style="opacity:0.7">
            </div>

            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:4px">
                <button type="button" onclick="closeEdit()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
/* ---- Modal Tambah ---- */
function openTambah() {
    document.getElementById('modal-tambah').style.display = 'flex';
}
function closeTambah() {
    document.getElementById('modal-tambah').style.display = 'none';
}
function hitungSubtotal() {
    const harga = parseFloat(document.getElementById('modal-harga').value) || 0;
    const qty   = parseFloat(document.getElementById('modal-qty').value) || 0;
    document.getElementById('modal-subtotal').value = (harga * qty).toFixed(2);
}
document.getElementById('modal-tambah').addEventListener('click', function(e) {
    if (e.target === this) closeTambah();
});

/* ---- Modal Edit ---- */
function openEdit(id, namaBarang, satuan, hargaSatuan, qty, subtotal) {
    // Set action form ke route update detail-faktur target
    document.getElementById('form-edit').action = '{{ url("detail-faktur") }}/' + id;

    // Set nilai text input modal dari baris data tabel yang dipilih
    document.getElementById('edit-nama-barang').value = namaBarang;
    document.getElementById('edit-satuan').value = satuan;
    document.getElementById('edit-harga').value = hargaSatuan;
    document.getElementById('edit-qty').value = qty;
    document.getElementById('edit-subtotal').value = subtotal;

    document.getElementById('modal-edit').style.display = 'flex';
}
function closeEdit() {
    document.getElementById('modal-edit').style.display = 'none';
}
function hitungSubtotalEdit() {
    const harga = parseFloat(document.getElementById('edit-harga').value) || 0;
    const qty   = parseFloat(document.getElementById('edit-qty').value) || 0;
    document.getElementById('edit-subtotal').value = (harga * qty).toFixed(2);
}
document.getElementById('modal-edit').addEventListener('click', function(e) {
    if (e.target === this) closeEdit();
});
</script>
@endsection