<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Harian Simpanan</title>

    <style>
        body {
            font-family: dejavusans;
            font-size: 11px;
            margin: 20px;
        }

        .judul {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
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
            background: #0dcaf0; /* info */
            color: #000;
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        tfoot th {
            background: #eee;
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

<div class="judul">LAPORAN TRANSAKSI SIMPANAN</div>
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

@if($simpanan->count() > 0)

@php
    $tdebit  = 0;
    $tkredit = 0;
@endphp

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Keterangan</th>
            <th>Debit</th>
            <th>Kredit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($simpanan as $s)
            <tr>
                <td class="text-center">{{ $s->tanggal }}</td>
                <td>{{ $s->jenis }}</td>
                <td>{{ $s->keterangan }}</td>
                <td class="text-end">{{ number_format($s->v_debit,0,',','.') }}</td>
                <td class="text-end">{{ number_format($s->v_kredit,0,',','.') }}</td>
            </tr>

            @php
                $tdebit  += $s->v_debit;
                $tkredit += $s->v_kredit;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">TOTAL</th>
            <th class="text-end">{{ number_format($tdebit,0,',','.') }}</th>
            <th class="text-end">{{ number_format($tkredit,0,',','.') }}</th>
        </tr>
    </tfoot>
</table>

@else
    <div class="no-data">
        Tidak ada transaksi simpanan.
    </div>
@endif

</body>
</html>
