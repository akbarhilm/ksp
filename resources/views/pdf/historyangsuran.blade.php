<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: dejavusans;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header td {
            vertical-align: middle;
        }

        .judul {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 8px 0;
        }

        .border th, .border td {
            border: 1px solid #000;
            padding: 6px;
        }

        .right { text-align: right; }
        .center { text-align: center; }
    </style>
</head>
<body>

{{-- HEADER --}}
<table class="header">
    <tr>
        <td width="80">
            <img src="{{ public_path('koperasi.png') }}" width="70">
        </td>
        <td>
            <b>KOPERASI SINAR MURNI SEJAHTERA</b><br>
            Jl. Raya Bumi Indah City Blok Ryc R No. 5,<br>
            Pasar Kemis – Tangerang Banten
        </td>
    </tr>
</table>

<hr>

<div class="judul">RIWAYAT PEMBAYARAN</div>

{{-- DATA PINJAMAN --}}
<table>
    <tr>
        <td width="25%">Nomor Pinjaman</td>
        <td width="25%">: {{ $pinjaman->id_pinjaman }}</td>
        <td width="25%">Nama Anggota</td>
        <td width="25%">: {{ $pinjaman->nasabah->nama }}</td>
    </tr>
    <tr>
        <td>Nomor Anggota</td>
        <td>: {{ str_pad($pinjaman->id_nasabah,5,'0',STR_PAD_LEFT) }}</td>
        <td>Jumlah Pinjaman</td>
        <td>: Rp {{ number_format($pinjaman->total_pinjaman,0,',','.') }}</td>
    </tr>
</table>

<br>

{{-- TABEL ANGSURAN --}}
<table class="border">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Tgl Bayar</th>
            <th width="10%">Angs</th>
            <th width="20%">Pokok</th>
            <th width="20%">Jasa</th>
            <th width="20%">Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalPokok = 0;
            $totalJasa = 0;
            $total = 0;
        @endphp
        @foreach($angsuran as $i => $a)
        @php
            $rowTotal = $a['pokok'] + $a['jasa'];
            $totalPokok += $a['pokok'];
            $totalJasa += $a['jasa'];
            $total += $rowTotal;
        @endphp
        <tr>
            <td class="center">{{ $i+1 }}</td>
            <td class="center">{{ $a['tanggal'] }}</td>
            <td class="center">{{ $a['angsuran'] }}</td>
            <td class="right">{{ number_format($a['pokok'],0,',','.') }}</td>
            <td class="right">{{ number_format($a['jasa'],0,',','.') }}</td>
            <td class="right">{{ number_format($rowTotal,0,',','.') }}</td>
        </tr>
        @endforeach

        <tr>
            <th colspan="3" class="right">TOTAL</th>
            <th class="right">{{ number_format($totalPokok,0,',','.') }}</th>
            <th class="right">{{ number_format($totalJasa,0,',','.') }}</th>
            <th class="right">{{ number_format($total,0,',','.') }}</th>
        </tr>
    </tbody>
</table>

<br>

Tanggal Cetak: {{ $tanggal_cetak }}

<br><br>

<table>
    <tr>
        <td width="60%"></td>
        <td class="center">
            Mengetahui,<br><br><br>
            <b>Kasir Koperasi</b>
        </td>
    </tr>
</table>

</body>
</html>
