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
        <div class="card-header"><span class="card-title">Item Pembelian</span></div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Barang</th>
                        <th>Quantity</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faktur->detailFaktur as $i => $d)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td>{{ $d->barang->nama_barang ?? $d->id_barang }}</td>
                        <td>{{ $d->quantity }} {{ $d->barang->satuan ?? '' }}</td>
                        <td class="text-right">Rp {{ number_format($d->subtotal_faktur, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="border-top:2px solid var(--border2)">
                        <td colspan="3" style="padding:14px 16px;text-align:right;font-weight:700;text-transform:uppercase;font-size:12px;color:var(--muted)">Total</td>
                        <td style="padding:14px 16px;text-align:right;font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--accent)">
                            Rp {{ number_format($faktur->total_jumlah_faktur, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection