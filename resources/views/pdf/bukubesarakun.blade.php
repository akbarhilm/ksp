<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Besar Akun</title>

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
        }

        .subjudul {
            text-align: center;
            margin-bottom: 15px;
            font-size: 12px;
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
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .saldo-awal {
            font-weight: bold;
            background: #f5f5f5;
        }
    </style>
</head>
<body>

<div class="judul">BUKU BESAR AKUN</div>

<div class="subjudul">
    {{ $akun->kode_akun }} - {{ $akun->nama_akun }} <br>
    Periode :
    {{ $tglAwal ?? '-' }} s/d {{ $tglAkhir ?? '-' }}
</div>

<table>
    <thead>
        <tr>
            <th width="12%">Tanggal</th>
            <th>Keterangan</th>
            <th width="18%">Debet</th>
            <th width="18%">Kredit</th>
            <th width="18%">Saldo</th>
        </tr>
    </thead>
    <tbody>

        @foreach($data as $row)
            <tr class="{{ $row[1] == 'Saldo Awal' ? 'saldo-awal' : '' }}">
                <td class="text-center">
                    {{ date('d-m-Y', strtotime($row[0])) }}
                </td>
                <td>{{ $row[1] }}</td>
                <td class="text-right">
                    {{ number_format($row[2],0,',','.') }}
                </td>
                <td class="text-right">
                    {{ number_format($row[3],0,',','.') }}
                </td>
                <td class="text-right">
                    {{ number_format($row[4],0,',','.') }}
                </td>
            </tr>
        @endforeach

    </tbody>
</table>

</body>
</html>
