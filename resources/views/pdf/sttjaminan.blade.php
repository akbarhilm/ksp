<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tanda Terima Surat Jaminan</title>

    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
        }

        .logo {
            position: absolute;
            left: 20px;
            top: 5px;
            width: 50px;
        }

        .instansi {
            font-weight: bold;
            font-size: 18px;
        }

        .alamat {
            font-size: 11px;
            line-height: 1.3;
        }

        hr {
            border: 1px solid #000;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .nik {
            position: absolute;
            right: 20px;
            top: 110px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 4px;
            vertical-align: top;
        }

        .field {
            width: 35%;
        }

        .ttd {
            margin-top: 40px;
            width: 100%;
        }

        .ttd td {
            text-align: center;
            padding-top: 30px;
        }

        .catatan {
            margin-top: 20px;
            font-size: 11px;
        }

        .cap {
            position: absolute;
            right: 120px;
            bottom: 140px;
            width: 110px;
            opacity: 0.85;
        }

    </style>
</head>
<body>

{{-- LOGO --}}
<img src="{{public_path('koperasi.png')}}" class="logo">

<div class="header">
    <div class="instansi">KOPERASI SIMPAN PINJAM<br>SINAR MURNI</div>
    <div class="alamat">
        Badan Hukum : No. 30/BH/XI/KUMKM/VI/2015
    </div>
</div>

<hr>


<div class="judul">
    TANDA TERIMA SURAT JAMINAN 
</div>

<p>Telah diterima Surat Jaminan atas :</p>

<table>
    <tr>
        <td class="field">NAMA</td>
        <td>: {{ $data['nama'] }}</td>
    </tr>
    <tr>
        <td>ALAMAT</td>
        <td>: {{ $data['alamat'] }}</td>
    </tr>
    @foreach ($data['jaminan'] as $j )
        @if($j['jenis'] == 'raw')
            <tr>
                <td>{{$loop->iteration}}</td>
                <td >: {{$j['ket']}}</td>
            </tr>
        @else
        <tr>
            <td>{{$loop->iteration.'. '.$j['jenis']}}</td>
            <td>: {{$j['ket']}}</td>
        </tr>
        @endif
    @endforeach
   
</table>

<br>

<p>
Jaminan tersebut disimpan selama yang bersangkutan menjadi anggota  
<b>Koperasi Simpan Pinjam SINAR MURNI</b>.
</p>

<div class="catatan">
    <strong>Catatan :</strong><br>
    1. Surat tersebut tidak bisa di fotocopy / dipinjam. Jika pinjaman belum lunas<br>
    2. Semua titipan tidak dapat diambil sebelum pinjaman lunas.<br>
    3. Simpan tanda terima ini dengan baik. Jika hilang Koperasi Simpan Pinjam SINAR MURNI tidak akan memberikan (SK) tersebut di atas.
</div>

<table class="ttd">
<tr>
<td style="width:60%;"></td>
<td style="width:40%;">
Pasar Kemis, {{ (new IntlDateFormatter('id_ID', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'dd MMMM yyyy'))
        ->format(new DateTime()) }}<br>
Yang menerima,<br><br><br><br>
<strong>{{ $data['ttd'] }}</strong>
</td>
</tr>
</table>

{{-- CAP --}}
{{-- <img src="{{ public_path('cap.png') }}" class="cap"> --}}

</body>
</html>
