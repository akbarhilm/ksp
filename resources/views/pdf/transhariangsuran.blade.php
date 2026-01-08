<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Harian Angsuran</title>

    <style>
        body {
            font-family: dejavusans;
            font-size: 11px;
            margin: 20px;
        }

        .header {
            margin-bottom: 10px;
        }

        .judul {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        .subjudul {
            text-align: center;
            font-size: 11px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background: #198754;
            color: #fff;
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        tfoot th {
            background: #eee;
            color: #000;
            font-weight: bold;
        }

        .no-data {
            text-align: center;
            padding: 15px;
            border: 1px solid #000;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="judul">LAPORAN TRANSAKSI ANGSURAN</div>
    <div class="subjudul">
        Tanggal Cetak :
        {{ (new IntlDateFormatter(
            'id_ID',
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            'dd MMMM yyyy'
        ))->format(new DateTime()) }}
    </div>
</div>

@if($angsuran->count() > 0)

@php
    $totalpokok = 0;
    $totalbunga = 0;
    $totaldenda = 0;
    $totalbayar = 0;
@endphp

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Anggota</th>
            <th>Bayar Pokok</th>
            <th>Bayar Bunga</th>
            <th>Bayar Denda</th>
            <th>Total Bayar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($angsuran as $a)
            <tr>
                <td class="text-center">{{ $a->tanggal }}</td>
                <td>
                    {{ str_pad($a->pinjaman->id_nasabah, 5, '0', STR_PAD_LEFT) }}
                    /
                    {{ $a->pinjaman->nasabah->nama ?? '-' }}
                </td>
                <td class="text-end">{{ number_format($a->bayar_pokok,0,',','.') }}</td>
                <td class="text-end">{{ number_format($a->bayar_bunga,0,',','.') }}</td>
                <td class="text-end">{{ number_format($a->bayar_denda,0,',','.') }}</td>
                <td class="text-end">{{ number_format($a->total_bayar,0,',','.') }}</td>
            </tr>

            @php
                $totalpokok += $a->bayar_pokok;
                $totalbunga += $a->bayar_bunga;
                $totaldenda += $a->bayar_denda;
                $totalbayar += $a->total_bayar;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">TOTAL</th>
            <th class="text-end">{{ number_format($totalpokok,0,',','.') }}</th>
            <th class="text-end">{{ number_format($totalbunga,0,',','.') }}</th>
            <th class="text-end">{{ number_format($totaldenda,0,',','.') }}</th>
            <th class="text-end">{{ number_format($totalbayar,0,',','.') }}</th>
        </tr>
    </tfoot>
</table>

@else
    <div class="no-data">
        Tidak ada transaksi angsuran.
    </div>
@endif

</body>
</html>
