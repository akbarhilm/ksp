<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Pembayaran</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size:12px; color:#000; }
        .header { width:100%; display:table; margin-bottom:6px; }
        .logo { width:70px; }
        .header-text { vertical-align:middle; padding-left:8px; font-size:12px; }
        .koperasi { font-weight:bold; font-size:14px; }
        .address { font-size:10px; margin-top:2px; }
        hr { border:1px solid #000; margin:6px 0; }

        .judul { text-align:center; font-weight:bold; font-size:16px; margin:6px 0 10px 0; }

        .meta, .data-table { width:100%; border-collapse:collapse; margin-bottom:10px; }
        .meta td { border:none; padding:2px 4px; vertical-align:top; font-size:11px; }
        .meta .label { width:140px; font-weight:600; }

        table.data-table th,
        table.data-table td {
            border:1px solid #000;
            padding:6px 8px;
            font-size:11px;
        }
        table.data-table th { background:#f0f0f0; font-weight:700; text-align:center; }

        .right { text-align:right; }
        .center { text-align:center; }

        .stamp {
            position:absolute;
            right:40px;
            top:90px;
            width:220px;
            opacity:0.9;
        }

        .footer { margin-top:14px; font-size:11px; }
        .ttd-row { width:100%; margin-top:24px; }
        .ttd-row td { border:none; vertical-align:top; padding-top:5px; }
        .cap { position:absolute; right:120px; bottom:100px; width:120px; opacity:0.85; }

        /* ensure table fits page */
        .table-wrap { overflow:hidden; }
    </style>
</head>
<body>

<table class="header">
    <tr>
        <td style="width:80px; border:none;">
            <img src="{{ public_path('koperasi.png') }}" class="logo" alt="logo">
        </td>
        <td class="header-text" style="border:none;">
            <div class="koperasi">{{ $koperasi_name ?? 'Koperasi Sinar Murni Sejahtera' }}</div>
            <div class="address">{{ $koperasi_address ?? 'Jl. Raya Bumi Indah City Blok Ryc R No. 5, Pasar Kemis - Tangerang Banten' }}</div>
        </td>
    </tr>
</table>

<hr>

<div class="judul">Riwayat Pembayaran</div>

<table class="meta">
    <tr>
        <td class="label">Nomor Pinjaman</td>
        <td>{{ $no_pinjaman }}</td>
        <td class="label">Nama Anggota</td>
        <td>{{ $nama }}</td>
    </tr>
    <tr>
        <td class="label">Nomor Anggota</td>
        <td>{{ $no_anggota }}</td>
        <td class="label">Jumlah Pinjaman</td>
        <td class="">Rp {{ number_format($jumlah_pinjaman,0,',','.') }}</td>
    </tr>
    <tr>
        <td class="label">Tenor</td>
        <td>{{ $tenor }}</td>
        <td class="label">Tanggal Pengajuan</td>
        <td>{{ $tgl_pengajuan }}</td>
    </tr>
    <tr>
        <td class="label">Tanggal Pelunasan</td>
        <td>{{ $tgl_pelunasan ?? '-' }}</td>
        <td class="label">Status Lunas</td>
        <td>{{ $status_lunas ?? '-' }}</td>
    </tr>
</table>

{{-- stamp image (opsional) --}}


<div class="table-wrap">
<table class="data-table">
    <thead>
        <tr>
            <th style="width:40px">No</th>
            <th style="width:130px">Tgl Bayar</th>
            <th style="width:80px">Angsuran</th>
            <th style="width:110px">Pokok</th>
            <th style="width:110px">Jasa</th>
            <th style="width:110px">Total</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; $sumAngsuran = 0; $sumPokok = 0; $sumJasa = 0; $sumTotal = 0; @endphp
        @forelse($angsuran as $item)
            <tr>
                <td class="center">{{ $no++ }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($item['tanggal'])->format('Y-m-d') }}</td>
                <td class="center">{{ $item['angsuran_label'] ?? $item['angsuran'] ?? $item['nomor'] ?? '' }}</td>
                <td class="right">Rp {{ number_format($item['pokok'] ?? 0,0,',','.') }}</td>
                <td class="right">Rp {{ number_format($item['jasa'] ?? 0,0,',','.') }}</td>
                <td class="right">Rp {{ number_format(($item['pokok'] ?? 0) + ($item['jasa'] ?? 0),0,',','.') }}</td>
            </tr>
            @php
                $sumAngsuran += ($item['angsuran'] ?? 0);
                $sumPokok += ($item['pokok'] ?? 0);
                $sumJasa += ($item['jasa'] ?? 0);
                $sumTotal += (($item['pokok'] ?? 0) + ($item['jasa'] ?? 0));
            @endphp
        @empty
            <tr>
                <td class="center" colspan="6">Tidak ada data pembayaran</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="3" class="right bold">Total</td>
            <td class="right bold">Rp {{ number_format($sumPokok,0,',','.') }}</td>
            <td class="right bold">Rp {{ number_format($sumJasa,0,',','.') }}</td>
            <td class="right bold">Rp {{ number_format($sumTotal,0,',','.') }}</td>
        </tr>
    </tbody>
</table>
</div>

<div class="footer">
    Tanggal Cetak : {{ $tanggal_cetak ?? date('d-m-Y') }}
</div>

<table class="ttd-row">
    <tr>
        <td style="width:30%;">
            Kasir Koperasi Murni
        </td>
        
    </tr>
</table>



</body>
</html>
