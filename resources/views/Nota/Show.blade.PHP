@extends('layouts.app')
@section('title', 'Detail Nota')
 
@section('content')
<div style="max-width:700px">
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-title">Nota — <span class="badge badge-green font-mono">{{ $nota->id_nota }}</span></span>
            <div class="flex gap-2">
                {{-- FIX: pakai $nota->id_nota secara eksplisit --}}
                <a href="{{ route('nota.print', $nota) }}" target="_blank" class="btn btn-primary btn-sm">🖨️ Cetak</a>
                <a href="{{ route('nota.edit',  $nota->id_nota) }}" class="btn btn-secondary btn-sm">Edit</a>
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
        <div class="card-header"><span class="card-title">Item Penjualan</span></div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nota->detailNota as $i => $d)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td>{{ $d->barang->nama_barang ?? $d->id_barang }}</td>
                        <td>{{ $d->banyaknya }} {{ $d->barang->satuan ?? '' }}</td>
                        <td class="text-right">Rp {{ number_format($d->subtotal_nota, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="border-top:2px solid var(--border2)">
                        <td colspan="3" style="padding:14px 16px;text-align:right;font-weight:700;text-transform:uppercase;font-size:12px;color:var(--muted);letter-spacing:.5px">Total</td>
                        <td style="padding:14px 16px;text-align:right;font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--green)">
                            Rp {{ number_format($nota->total_jumlah_nota, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection