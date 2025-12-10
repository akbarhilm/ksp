<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h3 {
            text-align: center;
            margin-bottom: 5px;
        }

        .periode {
            text-align: center;
            margin-bottom: 15px;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #333;
            padding: 5px;
        }

        th {
            background: #eee;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .total {
            background: #ddd;
        }

        .highlight {
            background: #cfe2ff;
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


<h4>PENDAPATAN</h4>
<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Akun</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pendapatanPerAkun as $row)
            <tr>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-end">{{ number_format($row['total'],0,',','.') }}</td>
            </tr>
        @endforeach
        <tr class="fw-bold total">
            <td colspan="2">TOTAL PENDAPATAN</td>
            <td class="text-end">{{ number_format($totalPendapatan,0,',','.') }}</td>
        </tr>
    </tbody>
</table>


<h4>BEBAN / BIAYA</h4>
<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Akun</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bebanPerAkun as $row)
            <tr>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-end">{{ number_format($row['total'],0,',','.') }}</td>
            </tr>
        @endforeach
        <tr class="fw-bold total">
            <td colspan="2">TOTAL BEBAN</td>
            <td class="text-end">{{ number_format($totalBeban,0,',','.') }}</td>
        </tr>
    </tbody>
</table>


<table>
    <tr class="highlight">
        <td>LABA / RUGI</td>
        <td class="text-end">
            {{ number_format($laba,0,',','.') }}
        </td>
    </tr>
</table>

</body>
</html>
