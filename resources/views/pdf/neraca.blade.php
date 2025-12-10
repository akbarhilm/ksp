<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Neraca</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        .row {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 5px;
        }

        .card {
            border: 1px solid #666;
        }

        .card-header {
            background: #0277ba;
            color: white;
            padding: 5px;
            font-weight: bold;
            text-align: center;
        }

        .km-header {
            background: #198754;
            color: white;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            border: 1px solid #999;
            padding: 4px;
        }

        .text-end {
            text-align: right;
        }

        .bg-info { background:#0277ba;color:white; }
        .bg-success { background:#198754;color:white; }
        .fw-bold { font-weight:bold; }

    </style>
</head>
<body>

<h3>Neraca ({{ date('d-m-Y', strtotime($tanggal)) }})</h3>

@php
    $totalAset = 0;
    $totalKewajiban = 0;
    $totalModal = 0;

    $asetCount = count($neraca['Aset']);
    $kmCount = count($neraca['Kewajiban']) + count($neraca['Modal']);
    $maxRows = max($asetCount, $kmCount);
@endphp

<div class="row">

    {{-- ASET --}}
    <div class="col">
        <div class="card">
            <div class="card-header">ASET</div>
            <table>
                <tbody>
                @for($i=0;$i<$maxRows;$i++)
                    @if(isset($neraca['Aset'][$i]))
                        <tr>
                            <td>{{ $neraca['Aset'][$i]['nama'] }}</td>
                            <td class="text-end">{{ number_format($neraca['Aset'][$i]['saldo'],0,',','.') }}</td>
                        </tr>
                        @php $totalAset += $neraca['Aset'][$i]['saldo']; @endphp
                    @else
                        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endif
                @endfor
                <tr class="fw-bold">
                    <td class="bg-info">TOTAL ASET</td>
                    <td class="bg-info text-end">{{ number_format($totalAset,0,',','.') }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- KEWAJIBAN & MODAL --}}
    <div class="col">
        <div class="card">
            <div class="card-header km-header">KEWAJIBAN & MODAL</div>
            <table>
                <tbody>
                @for($i=0;$i<$maxRows;$i++)
                    @php
                        $kmItem = null;
                        if($i < count($neraca['Kewajiban'])) {
                            $kmItem = $neraca['Kewajiban'][$i];
                            $totalKewajiban += $kmItem['saldo'];
                        } elseif($i - count($neraca['Kewajiban']) < count($neraca['Modal'])) {
                            $kmItem = $neraca['Modal'][$i - count($neraca['Kewajiban'])];
                            $totalModal += $kmItem['saldo'];
                        }
                    @endphp

                    @if($kmItem)
                        <tr>
                            <td>{{ $kmItem['nama'] }}</td>
                            <td class="text-end">{{ number_format($kmItem['saldo'],0,',','.') }}</td>
                        </tr>
                    @else
                        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endif
                @endfor
                <tr class="fw-bold">
                    <td class="bg-success">TOTAL KEWAJIBAN & MODAL</td>
                    <td class="bg-success text-end">
                        {{ number_format($totalKewajiban + $totalModal,0,',','.') }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
