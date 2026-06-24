<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota {{ $nota->id_nota }}</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family: Arial, sans-serif;
            font-size:13px;
            color:#000;
            background:#fff;

            /* agar border tetap muncul saat print */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page{
            width:148mm;
            min-height:210mm;
            margin:0 auto;
            padding:8mm 10mm 10mm;

            /* BORDER LUAR NOTA */
            border:1px solid #000;
        }

        /* =========================
           TANGGAL KANAN ATAS
        ========================= */
        .top-right{
            text-align:right;
            margin-bottom:3mm;
        }

        .top-right .tanggal-line{
            display:inline-block;
            border-bottom:1px solid #000;
            min-width:100px;
            font-size:12px;
            padding-bottom:1px;
            text-align:center;
        }

        /* =========================
           TUAN / TOKO
        ========================= */
        .tuan-toko{
            display:flex;
            flex-direction:column;
            align-items:flex-end;
            gap:4px;
            margin-bottom:5mm;
        }

        .tuan-toko .row{
            display:flex;
            align-items:baseline;
            gap:6px;
        }

        .tuan-toko .lbl{
            font-size:12px;
            min-width:32px;
        }

        .tuan-toko .val{
            border-bottom:1px solid #000;
            min-width:140px;
            font-size:12px;
            padding-bottom:1px;
            padding-left:2px;
        }

        /* =========================
           NOTA NO
        ========================= */
        .nota-no{
            display:flex;
            align-items:baseline;
            gap:8px;
            margin-bottom:3mm;
        }

        .nota-no .label{
            font-size:14px;
            font-weight:900;
            text-transform:uppercase;
            letter-spacing:1px;
        }

        .nota-no .val{
            border-bottom:1px solid #000;
            min-width:100px;
            font-size:13px;
            padding-bottom:1px;
            padding-left:4px;
        }

        /* =========================
           TABLE
        ========================= */
        table{
            width:100%;
            border-collapse:collapse;

            /* BORDER LUAR TABEL */
            border:1px solid #000;
        }

        table th,
        table td{
            border:1px solid #000;
            padding:4px 6px;
            font-size:12px;
        }

        table th{
            font-weight:700;
            text-align:center;
            font-size:11px;
            text-transform:uppercase;
        }

        .col-banyak{
            width:62px;
            text-align:center;
        }

        .col-harga{
            width:74px;
            text-align:right;
        }

        .col-jumlah{
            width:90px;
            text-align:right;
        }

        .empty-row td{
            height:28px;
        }

        /* =========================
           FOOTER
        ========================= */
        .footer{
            margin-top:8mm;
            display:flex;
            justify-content:space-between;
        }

        .sign-box{
            text-align:center;
            width:44%;
        }

        .sign-box .sign-title{
            font-size:12px;
            margin-bottom:22mm;
        }

        .sign-box .sign-line{
            border-top:1px solid #000;
            padding-top:3px;
            font-size:11px;
        }

        /* =========================
           BUTTON PRINT
        ========================= */
        .btn-print{
            display:block;
            margin:12px auto 0;
            padding:8px 30px;
            background:#222;
            color:#fff;
            border:none;
            font-size:13px;
            font-weight:700;
            cursor:pointer;
        }

        .btn-print:hover{
            background:#555;
        }

        /* =========================
           PRINT
        ========================= */
        @media print{

            @page{
                size:A5 portrait;
                margin:0;
            }

            .page{
                margin:0;
                padding:6mm 8mm;
                border:1px solid #000;
            }

            table,
            th,
            td{
                border:1px solid #000 !important;
            }

            .btn-print{
                display:none !important;
            }
        }

    </style>
</head>

<body>

<div class="page">

    {{-- TANGGAL --}}
    <div class="top-right">
        <span class="tanggal-line">
            {{ \Carbon\Carbon::parse($nota->tanggal_nota)->format('d-m-Y') }}
        </span>
    </div>

    {{-- TUAN / TOKO --}}
    <div class="tuan-toko">

        <div class="row">
            <span class="lbl">Tuan</span>

            <span class="val">
                {{ $nota->customer->nama_customer ?? '' }}
            </span>
        </div>

        <div class="row">
            <span class="lbl">Toko</span>

            <span class="val">
                {{ $nota->customer->alamat_customer ?? '' }}
            </span>
        </div>

    </div>

    {{-- NOTA NO --}}
    <div class="nota-no">

        <span class="label">
            Nota No.
        </span>

        <span class="val">
            {{ $nota->id_nota }}
        </span>

    </div>

    {{-- TABLE --}}
    <table>

        <thead>
            <tr>
                <th class="col-banyak">Banyaknya</th>
                <th>Nama Barang</th>
                <th class="col-harga">Harga</th>
                <th class="col-jumlah">Jumlah</th>
            </tr>
        </thead>

        <tbody>

            @php
                $jumlahItem = $nota->detailNota->count();
            @endphp

            @foreach($nota->detailNota as $d)

            @php
                $harga = $d->banyaknya > 0
                    ? $d->subtotal_nota / $d->banyaknya
                    : 0;
            @endphp

            <tr>

                <td class="col-banyak">
                    {{ $d->banyaknya }}
                    {{ $d->barang->satuan ?? '' }}
                </td>

                <td>
                    {{ $d->barang->nama_barang ?? $d->id_barang }}
                </td>

                <td class="col-harga">
                    {{ $harga > 0 ? number_format($harga, 0, ',', '.') : '' }}
                </td>

                <td class="col-jumlah">
                    {{ number_format($d->subtotal_nota, 0, ',', '.') }}
                </td>

            </tr>

            @endforeach

            {{-- BARIS KOSONG --}}
            @for($i = $jumlahItem; $i < 10; $i++)

            <tr class="empty-row">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            @endfor

            {{-- TOTAL --}}
            <tr>

                <td colspan="3"
                    style="
                        text-align:right;
                        font-weight:700;
                        padding-right:8px;
                        font-size:12px;
                    ">

                    Jumlah Rp.

                </td>

                <td class="col-jumlah" style="font-weight:700;">

                    {{ number_format($nota->total_jumlah_nota, 0, ',', '.') }}

                </td>

            </tr>

        </tbody>

    </table>

    {{-- TANDA TANGAN --}}
    <div class="footer">

        <div class="sign-box">

            <div class="sign-title">
                Tanda Terima
            </div>

            <div class="sign-line">
                &nbsp;
            </div>

        </div>

        <div class="sign-box">

            <div class="sign-title">
                Hormat Kami,
            </div>

            <div class="sign-line">

                (
                {{ $nota->pegawai->nama_pegawai ?? '....................' }}
                )

            </div>

        </div>

    </div>

</div>

<button class="btn-print" onclick="window.print()">
     Cetak Nota
</button>

</body>
</html>