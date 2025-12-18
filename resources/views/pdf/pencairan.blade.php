<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tanda Terima Pencairan Pinjaman</title>

    <style>
        body {
            font-family: dejavusans;
            font-size: 11px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #000;
            padding: 5px;
        }

        .no-border td {
            border: none;
        }

        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }

        .judul {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
        }

        hr {
            border: 0;
            border-top: 1px solid #000;
            margin: 8px 0;
        }

        .section {
            font-weight: bold;
            margin: 10px 0 5px;
        }
    </style>
</head>
<body>

@php
/* ================= TERBILANG ================= */
if (!function_exists('penyebut')) {
    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = ["","Satu","Dua","Tiga","Empat","Lima","Enam","Tujuh","Delapan","Sembilan","Sepuluh","Sebelas"];
        if ($nilai < 12) return " ".$huruf[$nilai];
        if ($nilai < 20) return penyebut($nilai - 10)." Belas";
        if ($nilai < 100) return penyebut($nilai/10)." Puluh".penyebut($nilai % 10);
        if ($nilai < 200) return " Seratus".penyebut($nilai - 100);
        if ($nilai < 1000) return penyebut($nilai/100)." Ratus".penyebut($nilai % 100);
        if ($nilai < 2000) return " Seribu".penyebut($nilai - 1000);
        if ($nilai < 1000000) return penyebut($nilai/1000)." Ribu".penyebut($nilai % 1000);
        if ($nilai < 1000000000) return penyebut($nilai/1000000)." Juta".penyebut($nilai % 1000000);
        return "";
    }
}
if (!function_exists('terbilang')) {
    function terbilang($nilai) {
        return $nilai == 0 ? "-" : trim(penyebut($nilai));
    }
}
if (!function_exists('bulatKhusus')) {
    function bulatKhusus($nilai) {
        $nilai = floor($nilai);
        $sisa = $nilai % 1000;
        if ($sisa == 0 || $sisa == 500) return $nilai;
        if ($sisa < 500) return $nilai + (500 - $sisa);
        return $nilai + (1000 - $sisa);
    }
}
@endphp

{{-- ================= HEADER ================= --}}
<table class="no-border">
    <tr>
        <td width="15%">
            <img src="{{ public_path('koperasi.png') }}" width="60">
        </td>
        <td width="85%">
            <b>KOPERASI SINAR MURNI SEJAHTERA</b><br>
            Jl. Raya Bumi Indah City Blok Rye.R No. 5<br>
            Pasar Kemis – Tangerang Banten
        </td>
    </tr>
</table>

<hr>

<div class="judul">TANDA TERIMA PENCAIRAN PINJAMAN</div>

{{-- ================= DATA ANGGOTA ================= --}}
<table>
    <tr>
        <td class="bold">Nomor Anggota</td>
        <td>{{ $data['no_anggota'] }}</td>
        <td class="bold">Nama</td>
        <td>{{ $data['nama'] }}</td>
    </tr>
    <tr>
        <td class="bold">Telepon</td>
        <td>{{ $data['telepon'] }}</td>
        <td class="bold">Tanggal Lahir</td>
        <td>{{ $data['tgl_lahir'] }}</td>
    </tr>
</table>

<br>

<p>
Telah diterima pinjaman dari <b>Koperasi Sinar Murni Sejahtera</b>
sejumlah <b>Rp {{ number_format($data['jumlah_pinjaman'],0,',','.') }}</b>
( {{ terbilang($data['jumlah_pinjaman']) }} Rupiah )  
pada tanggal {{ $data['tgl_cair'] }} dengan angsuran sebesar
<b>
Rp {{ number_format(
    bulatKhusus(
        ($data['jumlah_pinjaman']/$data['tenor']) *
        (($data['bunga']*$data['tenor']/100)+1)
    ),0,',','.'
) }}
</b>
selama {{ $data['tenor'] }} bulan,
dengan nomor pinjaman {{ $data['id'] }}.
</p>

{{-- ================= DATA PINJAMAN ================= --}}
<div class="section">Data Pinjaman</div>

<table>
    <tr>
        <td>Jumlah Pinjaman</td>
        <td class="right">{{ number_format($data['jumlah_pinjaman'],0,',','.') }}</td>
    </tr>
    <tr>
        <td>Provisi</td>
        <td class="right">{{ number_format($data['provisi'],0,',','.') }}</td>
    </tr>
    <tr>
        <td>Materai</td>
        <td class="right">{{ number_format($data['materai'],0,',','.') }}</td>
    </tr>
    <tr>
        <td>Biaya Survey</td>
        <td class="right">{{ number_format($data['survey'],0,',','.') }}</td>
    </tr>
    <tr>
        <td>Simpanan Pokok</td>
        <td class="right">{{ number_format($data['simpanan_pokok'],0,',','.') }}</td>
    </tr>
    <tr>
        <td>Asuransi Jiwa</td>
        <td class="right">{{ number_format($data['asuransi'],0,',','.') }}</td>
    </tr>
    @if($data['pinjamanlama']>0)
     <tr>
        <td>Sisa Pinjaman Sebelumnya</td>
        <td class="right">{{ number_format($data['pinjamanlama'],0,',','.') }}</td>
    </tr>
    @endif
    <tr class="bold">
        <td>Diterima Bersih</td>
        <td class="right">{{ number_format($data['diterima_bersih'],0,',','.') }}</td>
    </tr>
</table>

<br>

Tanggal:
{{ (new IntlDateFormatter('id_ID', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'dd MMMM yyyy'))
        ->format(new DateTime()) }}

<br><br>

{{-- ================= TTD ================= --}}
<table class="no-border">
    <tr class="center">
        <td width="33%">Disetujui<br><br><br>______________</td>
        <td width="33%">Mengetahui<br><br><br>______________</td>
        <td width="33%">Peminjam<br><br><br>{{ $data['nama'] }}</td>
    </tr>
</table>

</body>
</html>
