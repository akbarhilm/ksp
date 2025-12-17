<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Penarikan Simpanan</title>

    <style>
        body {
            font-family: dejavusans;
            font-size: 11px;
            margin: 20px;
        }

        .header {
            width: 100%;
            margin-bottom: 10px;
        }

        .logo {
            width: 60px;
        }

        .koperasi {
            font-size: 14px;
            font-weight: bold;
        }

        .alamat {
            font-size: 10px;
        }

        hr {
            border: 1px solid #000;
            margin: 8px 0 12px 0;
        }

        .judul {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 15px;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
            vertical-align: top;
        }

        .field {
            width: 35%;
        }

        .nilai {
            font-weight: bold;
        }

        .kotak {
            border: 1px solid #000;
            padding: 8px;
            margin-top: 10px;
        }

        .right {
            text-align: right;
        }

        .ttd {
            margin-top: 35px;
            width: 100%;
        }

        .ttd td {
            text-align: center;
            padding-top: 40px;
        }
    </style>
</head>
<body>

<table class="header">
    <tr>
        <td width="70">
            <img src="{{ public_path('koperasi.png') }}" class="logo">
        </td>
        <td>
            <div class="koperasi">KOPERASI SIMPAN PINJAM SINAR MURNI</div>
            <div class="alamat">
                Badan Hukum No. 30/BH/XI/KUMKM/VI/2015<br>
                Pasar Kemis – Tangerang
            </div>
        </td>
    </tr>
</table>

<hr>

<div class="judul">BUKTI PENARIKAN SIMPANAN</div>

<table>
    <tr>
        <td class="field">No Transaksi</td>
        <td>: {{ $data['no_transaksi'] }}</td>
    </tr>
    <tr>
        <td>Nama Anggota</td>
        <td>: {{ $data['nama'] }}</td>
    </tr>
    <tr>
        <td>No Anggota</td>
        <td>: {{ $data['no_anggota'] }}</td>
    </tr>
    
    <tr>
        <td>Tanggal Penarikan</td>
        <td>: {{ date('d-m-Y', strtotime($data['tanggal'])) }}</td>
    </tr>
</table>

<div class="kotak">
    <table>
        <tr>
            <td>Jumlah Penarikan</td>
            <td class="right nilai">
                Rp {{ number_format($data['jumlah'],0,',','.') }}
            </td>
        </tr>
        <tr>
            <td>Terbilang</td>
            <td><i>{{ $data['terbilang'] }} Rupiah</i></td>
        </tr>
    </table>
</div>

<p>
Demikian bukti penarikan simpanan ini dibuat untuk dipergunakan sebagaimana mestinya.
</p>

<table class="ttd">
    <tr>
        <td width="33%">
            Menyetujui<br><br><br>
            <strong>Petugas</strong>
        </td>
        <td width="33%">
            <br><br><br>
            <strong>Kasir</strong>
        </td>
        <td width="33%">
            Pasar Kemis, {{ date('d-m-Y') }}<br>
            Yang Menerima<br><br><br>
            <strong>{{ $data['nama'] }}</strong>
        </td>
    </tr>
</table>

</body>
</html>
