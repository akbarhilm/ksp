<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tanda Terima Surat Jaminan</title>

    <style>
        body {
            font-family: dejavusans;
            font-size: 12px;
            margin: 20px 25px;
        }

        .header {
            text-align: center;
        }

        .logo {
            position: absolute;
            left: 25px;
            top: 20px;
            width: 55px;
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
            margin-top: 15px;
            margin-bottom: 18px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 18px;
            font-size: 15px;
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
            margin-top: 45px;
            width: 100%;
        }

        .ttd td {
            text-align: center;
            padding-top: 35px;
        }

        .catatan {
            margin-top: 20px;
            font-size: 11px;
            line-height: 1.4;
        }

        .cap {
            position: absolute;
            right: 130px;
            bottom: 150px;
            width: 110px;
            opacity: 0.85;
        }
    </style>
</head>
<body>

{{-- LOGO --}}

<div class="header">
<img src="{{ public_path('koperasi.png') }}" class="logo">

    <div class="instansi">
        KOPERASI SIMPAN PINJAM<br>
        SINAR MURNI
    </div>
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

    @foreach ($data['jaminan'] as $j)
        <tr>
            <td>
                {{ $loop->iteration }}
                @if($j['jenis'] !== 'raw')
                    . {{ strtoupper($j['jenis']) }}
                @endif
            </td>
            <td>: {{ $j['ket'] }}</td>
        </tr>
    @endforeach
</table>

<br>

<p>
    Jaminan tersebut disimpan selama yang bersangkutan menjadi anggota  
    <b>Koperasi Simpan Pinjam SINAR MURNI</b>.
</p>

<div class="catatan">
    <strong>Catatan :</strong><br>
    1. Surat tersebut tidak bisa difotocopy / dipinjam jika pinjaman belum lunas.<br>
    2. Semua titipan tidak dapat diambil sebelum pinjaman lunas.<br>
    3. Simpan tanda terima ini dengan baik. Jika hilang, Koperasi Simpan Pinjam
       SINAR MURNI tidak akan memberikan kembali surat jaminan tersebut.
</div>

<table class="ttd">
    <tr>
        <td style="width:60%"></td>
        <td style="width:40%">
            Pasar Kemis,
            {{ (new IntlDateFormatter(
                'id_ID',
                IntlDateFormatter::NONE,
                IntlDateFormatter::NONE,
                null,
                null,
                'dd MMMM yyyy'
            ))->format(new DateTime()) }}
            <br>
            Yang menerima,
            <br><br><br><br>
            <strong>{{ $data['ttd'] }}</strong>
        </td>
    </tr>
</table>

{{-- CAP / STEMPEL --}}
{{-- <img src="{{ public_path('cap.png') }}" class="cap"> --}}

</body>
</html>
