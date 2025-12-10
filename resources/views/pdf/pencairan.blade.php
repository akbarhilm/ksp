<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tanda Terima Pencairan Pinjaman</title>

    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .logo {
            width: 80px;
        }

        .header-text {
            vertical-align: middle;
            padding-left: 10px;
            font-size: 12px;
        }

        .nama-koperasi {
            font-weight: bold;
        }

        hr {
            border: 1px solid black;
            margin: 10px 0;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #000;
            padding: 5px;
            font-size: 11px;
        }

        .no-border td {
            border: none;
        }

        .center { text-align:center; }
        .right { text-align:right; }
        .bold { font-weight:bold; }

        .section {
            margin-top: 15px;
            font-weight: bold;
        }

        .ttd {
            margin-top: 40px;
            width: 100%;
        }

        .cap {
            position:absolute;
            left:30px;
            bottom:140px;
            width:120px;
            opacity:0.8;
        }

    </style>
</head>
<body>
@php
      if (!function_exists('penyebut')) {
function penyebut($Nilai) {
		$Nilai = abs($Nilai);
		$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
		$temp = "";
		if ($Nilai < 12) {
			$temp = " ". $huruf[$Nilai];
		} else if ($Nilai <20) {
			$temp = penyebut($Nilai - 10). " Belas";
		} else if ($Nilai < 100) {
			$temp = penyebut($Nilai/10)." Puluh". penyebut($Nilai % 10);
		} else if ($Nilai < 200) {
			$temp = " Seratus" . penyebut($Nilai - 100);
		} else if ($Nilai < 1000) {
			$temp = penyebut($Nilai/100) . " Ratus" . penyebut($Nilai % 100);
		} else if ($Nilai < 2000) {
			$temp = " Seribu" . penyebut($Nilai - 1000);
		} else if ($Nilai < 1000000) {
			$temp = penyebut($Nilai/1000) . " Ribu" . penyebut($Nilai % 1000);
		} else if ($Nilai < 1000000000) {
			$temp = penyebut($Nilai/1000000) . " Juta" . penyebut($Nilai % 1000000);
		} else if ($Nilai < 1000000000000) {
			$temp = penyebut($Nilai/1000000000) . " Milyar" . penyebut(fmod($Nilai,1000000000));
		} else if ($Nilai < 1000000000000000) {
			$temp = penyebut($Nilai/1000000000000) . " Trilyun" . penyebut(fmod($Nilai,1000000000000));
		}     
		return $temp;
	}
}
if (!function_exists('terbilang')) {
    function terbilang($Nilai) {

		if($Nilai<0) {
			$hasil = "minus ". trim(penyebut($Nilai))." ";
		}
		elseif ($Nilai==0) {
			$hasil = "-";	
		} 
		else{ 
			$hasil = trim(penyebut($Nilai))." ";
		}     		
		return $hasil;
	}
}
if (!function_exists('bulatKhusus')) {
function bulatKhusus($nilai)
{
     $nilai = floor($nilai);
    $sisa = $nilai % 1000;

    // jika sudah bulat ribuan
    if ($sisa == 0) {
        return $nilai;
    }

    // jika tepat 500, biarkan
    if ($sisa == 500) {
        return $nilai;
    }

    // jika di bawah 500, naikkan ke 500
    if ($sisa < 500) {
        return $nilai + (500 - $sisa);
    }

    // jika di atas 500, naikkan ke 1000
    return $nilai + (1000 - $sisa);
}
}
@endphp
<table class="header">
<tr>
    <td style="width:90px;border:none">
        <img src="{{ public_path('koperasi.png') }}" class="logo">
    </td>
    <td class="header-text">
        <div class="nama-koperasi">Koperasi Sinar Murni Sejahtera</div>
        Jl. Raya Bumi Indah City Blok Rye.R No. 5, Pasar Kemis – Tangerang Banten
    </td>
</tr>
</table>

<hr>

<div class="judul">TANDA TERIMA PENCAIRAN PINJAMAN</div>

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
Telah terima pinjaman dari Koperasi Sinar Murni Sejahtera uang sejumlah: 
<b>Rp {{ number_format($data['jumlah_pinjaman'],0,',','.') }}</b> ({{terbilang($data['jumlah_pinjaman'])}}) Rupiah
<br>
pada tanggal {{ $data['tgl_cair'] }}  
dengan angsuran sebesar <b>Rp {{number_format(bulatKhusus($data['jumlah_pinjaman']/$data['tenor']*(($data['bunga']*$data['tenor']/100)+1)),2,',','.')}}</b> selama {{ $data['tenor'] }} bulan,  
dengan nomor pinjaman {{ $data['id'] }}.
</p>

<div class="section">Data Pinjaman</div>

<table>

<tr>
    <td>Jumlah Pinjaman</td>
    <td colspan="2" class="right">{{ number_format($data['jumlah_pinjaman'],0,',','.') }}</td>
</tr>
<tr>
    <td>Provisi</td>
    <td colspan="2" class="right">{{ number_format($data['provisi'],0,',','.') }}</td>
</tr>
<tr>
    <td>Materai</td>
    <td colspan="2" class="right">{{ number_format($data['materai'],0,',','.') }}</td>
</tr>
<tr>
    <td>Biaya Survey</td>
    <td colspan="2" class="right">{{ number_format($data['survey'],0,',','.') }}</td>
</tr>
<tr>
    <td>Simpanan Pokok</td>
    <td colspan="2" class="right">{{ number_format($data['simpanan_pokok'],0,',','.') }}</td>
</tr>

<tr>
    <td>Asuransi Jiwa</td>
    <td colspan="2" class="right">{{ number_format($data['asuransi'],0,',','.') }}</td>
</tr>
<tr class="bold">
    <td>Diterima Bersih</td>
    <td colspan="2" class="right">{{ number_format($data['diterima_bersih'],0,',','.') }}</td>
</tr>
</table>

<br>
Tanggal : {{ (new IntlDateFormatter('id_ID', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'dd MMMM yyyy'))
        ->format(new DateTime()) }}

<table class="ttd no-border">
<tr>
    <td style="width:40%"> <br><br><br>Disetujui</td>
    <td style="width:40%">
        <br><br><br>
        Mengetahui
    </td>
    <td style="width:40%">
        <br><br><br>Peminjam
        
    </td>
</tr>
</table>


</body>
</html>
