<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Saldo Akun</title>

    <style>
        body {
            font-family: dejavusans;
            font-size: 11px;
            margin: 20px;
        }

        .judul {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .periode {
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
            background: #eee;
            text-align: center;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        tfoot th {
            background: #ddd;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="judul">LAPORAN SALDO AKUN</div>

<div class="periode">
    Periode :
    {{ request('tanggal_awal') ?? '-' }}
    s/d
    {{ request('tanggal_akhir') ?? '-' }}
</div>

<table>
    <thead>
        <tr>
            <th width="12%">Kode Akun</th>
            <th>Nama Akun</th>
            <th width="18%">Debet</th>
            <th width="18%">Kredit</th>
            <th width="18%">Saldo</th>
        </tr>
    </thead>
    <tbody>

        @php
            $totalDebet  = 0;
            $totalKredit = 0;
            $totalSaldo  = 0;
        @endphp

        @foreach($result as $row)
            <tr>
                <td class="text-center">{{ $row[0] }}</td>
                <td>{{ $row[1] }}</td>
                <td class="text-right">{{ number_format($row[2],0,',','.') }}</td>
                <td class="text-right">{{ number_format($row[3],0,',','.') }}</td>
                <td class="text-right">{{ number_format($row[4],0,',','.') }}</td>
            </tr>

            @php
                $totalDebet  += $row[2];
                $totalKredit += $row[3];
                $totalSaldo  += $row[4];
            @endphp
        @endforeach

    </tbody>

    <tfoot>
        <tr>
            <th colspan="2">TOTAL</th>
            <th class="text-right">{{ number_format($totalDebet,0,',','.') }}</th>
            <th class="text-right">{{ number_format($totalKredit,0,',','.') }}</th>
            <th class="text-right">{{ number_format($totalSaldo,0,',','.') }}</th>
        </tr>
    </tfoot>
</table>

</body>
</html>
