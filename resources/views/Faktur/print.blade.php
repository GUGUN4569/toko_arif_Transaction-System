<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur Penjualan {{ $faktur->no_faktur }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            background: #fff;
            color: #000;
            font-size: 11px;
        }

        /* Border keseluruhan faktur */
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 10mm 12mm;
            background: white;
            border: 1px solid #000;  /* border luar keseluruhan faktur */
        }

        /* Kop */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
        }
        .toko h1 {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .toko p {
            font-size: 9px;
            line-height: 1.2;
        }
        .doc-info {
            text-align: right;
            font-size: 10px;
            line-height: 1.4;
        }

        /* Info baris */
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin: 12px 0 6px 0;
            border-bottom: 1px solid #000;
            padding-bottom: 6px;
        }
        .info-left, .info-right {
            width: 48%;
        }
        .info-row {
            display: flex;
            gap: 6px;
            margin-bottom: 3px;
        }
        .info-label {
            font-weight: 700;
            min-width: 70px;
        }
        .ket-line {
            margin: 6px 0;
            padding-bottom: 4px;
            border-bottom: 1px solid #000;
        }

        /* TABEL dengan border penuh (7 kolom) */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 10px;
            border: 1px solid #000;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 6px 4px;
            vertical-align: top;
        }
        .items-table th {
            text-align: center;
            font-weight: 900;
            text-transform: uppercase;
            background-color: #f2f2f2;
        }
        .items-table td {
            text-align: left;
        }
        .col-no {
            width: 30px;
            text-align: center;
        }
        .col-jumlah {
            width: 55px;
            text-align: center;
        }
        .col-sat {
            width: 50px;
            text-align: center;
        }
        .col-harga {
            width: 90px;
            text-align: right;
        }
        .col-disc {
            width: 50px;
            text-align: center;
        }
        .col-subtotal {
            width: 100px;
            text-align: right;
        }

        /* Area total + pemeriksaan (flex dengan posisi: kiri = pemeriksaan, kanan = grand total) */
        .bottom-area {
            display: flex;
            justify-content: space-between;
            margin-top: 16px;
            border: 1px solid #000;
            padding: 12px;
        }
        /* Pemeriksaan (kiri) */
        .check-box {
            width: 48%;
            padding-right: 12px;
        }
        .check-item {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 20px;
        }
        .check-label {
            font-weight: 700;
            width: 70px;
        }
        .check-line {
            border-bottom: 1px solid #000;
            flex-grow: 1;
            margin-left: 8px;
            min-width: 120px;
        }
        /* Grand Total (kanan) */
        .total-box {
            width: 48%;
            padding-left: 12px;
            border-left: 1px solid #000;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }
        .total-row.grand {
            font-weight: 900;
            font-size: 13px;
            border-top: 1px solid #000;
            margin-top: 4px;
            padding-top: 6px;
        }

        /* Tombol cetak */
        .btn-print {
            display: block;
            margin: 20px auto;
            padding: 8px 24px;
            background: #1e1e1e;
            color: white;
            border: none;
            font-family: monospace;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-print:hover {
            background: #333;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .page {
                margin: 0;
                padding: 8mm 10mm;
                width: 100%;
                border: 1px solid #000 !important;
            }
            .btn-print {
                display: none;
            }
            @page {
                size: A4;
                margin: 0;
            }
            .items-table th, .items-table td,
            .bottom-area {
                border-color: #000 !important;
            }
        }
    </style>
</head>
<body>

<div class="page">
    <!-- KOP -->
    <div class="header">
        <div class="toko">
            <h1>GRAM</h1>
            <p>Jl. Suka Indah Barus - Sukabumi</p>
            <p>Telp. (0266) 123456</p>
        </div>
        <div class="doc-info">
            <div><strong>FAKTUR PENJUALAN</strong></div>
            <div>No. {{ $faktur->no_faktur ?? 'NF-'.date('ymd').rand(100,999) }}</div>
            <div>Status : {{ $faktur->status ?? 'UNLIK' }}</div>
            <div>Tanggal : {{ \Carbon\Carbon::parse($faktur->tanggal_faktur)->format('d/m/Y') }}</div>
            <div>Hal : 1</div>
        </div>
    </div>

    <!-- Info Tujuan -->
    <div class="info-grid">
        <div class="info-left">
            <div class="info-row">
                <span class="info-label">KEPADA YTH:</span>
                <span><strong>{{ $faktur->supplier->nama_supplier ?? 'TK ARIF/AGUS SALIM -0193' }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Alamat</span>
                <span>{{ $faktur->supplier->alamat_supplier ?? 'IP.LIMUS BUK 10/05 SUKA INDAH BARUS' }}</span>
            </div>
        </div>
        <div class="info-right">
            <div class="info-row">
                <span class="info-label">KASIR</span>
                <span>: {{ $faktur->pegawai->nama_pegawai ?? 'PIPIH' }}</span>
            </div>
        </div>
    </div>

    <div class="ket-line">
        <span><strong>KET:</strong> {{ $faktur->keterangan ?? '-' }}</span>
    </div>

    <!-- TABEL PRODUK -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="col-no">NO</th>
                <th>DESKRIPSI PRODUK</th>
                <th class="col-jumlah">Jumlah</th>
                <th class="col-sat">sat</th>
                <th class="col-harga">Harga</th>
                <th class="col-disc">disc</th>
                <th class="col-subtotal">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($faktur->detailFaktur as $index => $item)
            @php
                $qty = $item->quantity;
                $satuan = $item->barang->satuan ?? 'PCS';
                $hargaSatuan = ($qty > 0) ? $item->subtotal_faktur / $qty : 0;
                $discount = $item->diskon ?? '-';
            @endphp
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                <td>{{ $item->barang->nama_barang ?? $item->id_barang }}</td>
                <td class="col-jumlah">{{ $qty }}</td>
                <td class="col-sat">{{ $satuan }}</td>
                <td class="col-harga">{{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                <td class="col-disc">{{ $discount }}</td>
                <td class="col-subtotal">{{ number_format($item->subtotal_faktur, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @php $itemCount = $faktur->detailFaktur->count(); @endphp
            @for($i = $itemCount; $i < 8; $i++)
            <tr>
                <td class="col-no">&nbsp;</td>
                <td>&nbsp;</td>
                <td class="col-jumlah">&nbsp;</td>
                <td class="col-sat">&nbsp;</td>
                <td class="col-harga">&nbsp;</td>
                <td class="col-disc">&nbsp;</td>
                <td class="col-subtotal">&nbsp;</td>
            </tr>
            @endfor
        </tbody>
    </table>

    <!-- AREA TOTAL & PEMERIKSAAN (kiri: Diperiksa/Dikirim/Diterima, kanan: Grand Total) -->
    <div class="bottom-area">
        <!-- Kolom Pemeriksaan (kiri) -->
        <div class="check-box">
            <div class="check-item">
                <span class="check-label">Diperiksa</span>
                <span class="check-line"></span>
            </div>
            <div class="check-item">
                <span class="check-label">Dikirim</span>
                <span class="check-line"></span>
            </div>
            <div class="check-item">
                <span class="check-label">Diterima</span>
                <span class="check-line"></span>
            </div>
        </div>

        <!-- Grand Total (kanan) -->
        <div class="total-box">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($faktur->total_jumlah_faktur, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Diskon</span>
                <span>Rp 0</span>
            </div>
            <div class="total-row grand">
                <span>GRAND TOTAL</span>
                <span>Rp {{ number_format($faktur->total_jumlah_faktur, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

</div>

<button class="btn-print" onclick="window.print()">🖨️ CETAK FAKTUR</button>

</body>
</html>