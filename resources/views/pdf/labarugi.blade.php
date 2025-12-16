<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>

    <style>
        body {
            font-family: dejavusans;
            font-size: 11px;
            color: #000;
        }

        h3 {
            text-align: center;
            margin-bottom: 4px;
        }

        .periode {
            text-align: center;
            margin-bottom: 12px;
            font-size: 11px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #eeeeee;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .total {
            background-color: #dddddd;
            font-weight: bold;
        }

        .highlight {
            background-color: #cfe2ff;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h3>LAPORAN LABA / RUGI</h3>

<div class="periode">
    Periode {{ date('d-m-Y', strtotime($tanggalAwal)) }}
    s/d {{ date('d-m-Y', strtotime($tanggalAkhir)) }}
</div>

{{-- ================= PENDAPATAN ================= --}}
<h4>PENDAPATAN</h4>

<table>
    <thead>
        <tr>
            <th width="15%">Kode</th>
            <th width="55%">Akun</th>
            <th width="30%">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pendapatanPerAkun as $row)
        <tr>
            <td>{{ $row['kode'] }}</td>
            <td>{{ $row['nama'] }}</td>
            <td class="text-end">
                {{ number_format($row['total'],0,',','.') }}
            </td>
        </tr>
        @endforeach
        <tr class="total">
            <td colspan="2">TOTAL PENDAPATAN</td>
            <td class="text-end">
                {{ number_format($totalPendapatan,0,',','.') }}
            </td>
        </tr>
    </tbody>
</table>

{{-- ================= BEBAN ================= --}}
<h4>BEBAN / BIAYA</h4>

<table>
    <thead>
        <tr>
            <th width="15%">Kode</th>
            <th width="55%">Akun</th>
            <th width="30%">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bebanPerAkun as $row)
        <tr>
            <td>{{ $row['kode'] }}</td>
            <td>{{ $row['nama'] }}</td>
            <td class="text-end">
                {{ number_format($row['total'],0,',','.') }}
            </td>
        </tr>
        @endforeach
        <tr class="total">
            <td colspan="2">TOTAL BEBAN</td>
            <td class="text-end">
                {{ number_format($totalBeban,0,',','.') }}
            </td>
        </tr>
    </tbody>
</table>

{{-- ================= LABA RUGI ================= --}}
<table>
    <tr class="highlight">
        <td width="70%">LABA / RUGI</td>
        <td width="30%" class="text-end">
            {{ number_format($laba,0,',','.') }}
        </td>
    </tr>
</table>

</body>
</html>
