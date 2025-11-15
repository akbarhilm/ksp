<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Surat Perjanjian Hutang</title>
    {{-- <link rel="stylesheet" href="{{ public_path('css/pdf.css') }}"> --}}
    <style>
        body {
    font-family: sans-serif;
    font-size: 12px;
    line-height: 1.35;
}


h1, h2, h3, h4, h5, h6 {
    margin: 0;
    padding: 0;
}

.text-center { text-align: center; }
.text-right { text-align: right; }
.text-left { text-align: left; }
.text-justify { text-align: justify; }

.text-sm { font-size: 14px; }
.text-xs { font-size: 12px; }

.table {
    width: 100%;
    border:solid 1px;
}
.table tr {
border: solid 1px;
}
.table td {
    padding: 3px 4px;
    vertical-align: top;
}
.pasal td:first-child {
    width: 20px;
    vertical-align: top;
}

.pasal td:last-child {
    vertical-align: top;
}

.table-borderless td {
    border: none !important;
}

.underline { text-decoration: underline; }

.mt-2 { margin-top: 8px; }
.mt-3 { margin-top: 12px; }
.mt-4 { margin-top: 16px; }
.mt-5 { margin-top: 20px; }

.mb-0 { margin-bottom: 0; }
.mb-2 { margin-bottom: 8px; }
.mb-4 { margin-bottom: 16px; }

.page {
    width: 100%;
    max-width: 210mm;
    margin: 0 auto;
    padding: 15px 20px;
    background: white;
}

        </style>
</head>

<body>
    <div class="page">
    <!-- TITLE -->
    <div class="text-center mb-2 mt-3">
        <h3 class="underline">Neraca</h3>
        <div class="text-md">Per Periode</div>
    </div>       
 <h3 class="mt-3">Aktiva</h3>
<table class="table">
@foreach($ledger as $row)
    @if($row->akun->tipe_akun == 'Aset')
    <tr>
        <td>{{ $row->akun->nama_akun }}</td>
        <td class="text-right">{{ number_format($row->saldo,0) }}</td>
    </tr>
    @endif
@endforeach
<tr>
    <td><b>Total Aktiva / Aset<b></td>
    <td class ="text-right"><b>{{number_format($total['aset'],0)}}<b></td>
</tr>
</table>
<h3 class="mt-3">Kewajiban</h3>
<table class="table">
@foreach($ledger as $row)
    @if($row->akun->tipe_akun == 'Kewajiban')
    <tr>
        <td>{{ $row->akun->nama_akun }}</td>
        <td class="text-right">{{ number_format($row->saldo,0) }}</td>
    </tr>
    @endif
@endforeach
<tr style="border-top:solid 1px">
    <td class=""><b>Total Kewajiban<b></td>
    <td class ="text-right"><b>{{number_format($total['wajib'],0)}}<b></td>
</tr>
</table>
    </div>
</body>
</html>