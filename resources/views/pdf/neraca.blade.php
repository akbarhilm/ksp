<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Neraca</title>

    <style>
        body {
            font-family: dejavusans;
            font-size: 11px;
            color: #000;
        }

        h3 {
            text-align: center;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #999;
            padding: 4px;
        }

        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }

        .bg-aset {
            background-color: #0277ba;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .bg-km {
            background-color: #198754;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }
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

{{-- ===== LAYOUT 2 KOLOM AMAN mPDF ===== --}}
<table>
    <tr>
        {{-- ================= ASET ================= --}}
        <td width="50%" valign="top">
            <table>
                <tr>
                    <td colspan="2" class="bg-aset">ASET</td>
                </tr>

                @for($i = 0; $i < $maxRows; $i++)
                    @if(isset($neraca['Aset'][$i]))
                        <tr>
                            <td>{{ $neraca['Aset'][$i]['nama'] }}</td>
                            <td class="text-end">
                                {{ number_format($neraca['Aset'][$i]['saldo'],0,',','.') }}
                            </td>
                        </tr>
                        @php $totalAset += $neraca['Aset'][$i]['saldo']; @endphp
                    @else
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endif
                @endfor

                <tr class="fw-bold">
                    <td class="bg-aset">TOTAL ASET</td>
                    <td class="bg-aset text-end">
                        {{ number_format($totalAset,0,',','.') }}
                    </td>
                </tr>
            </table>
        </td>

        {{-- ================= KEWAJIBAN & MODAL ================= --}}
        <td width="50%" valign="top">
            <table>
                <tr>
                    <td colspan="2" class="bg-km">KEWAJIBAN & MODAL</td>
                </tr>

                @for($i = 0; $i < $maxRows; $i++)
                    @php
                        $kmItem = null;
                        if ($i < count($neraca['Kewajiban'])) {
                            $kmItem = $neraca['Kewajiban'][$i];
                            $totalKewajiban += $kmItem['saldo'];
                        } elseif ($i - count($neraca['Kewajiban']) < count($neraca['Modal'])) {
                            $kmItem = $neraca['Modal'][$i - count($neraca['Kewajiban'])];
                            $totalModal += $kmItem['saldo'];
                        }
                    @endphp

                    @if($kmItem)
                        <tr>
                            <td>{{ $kmItem['nama'] }}</td>
                            <td class="text-end">
                                {{ number_format($kmItem['saldo'],0,',','.') }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endif
                @endfor

                <tr class="fw-bold">
                    <td class="bg-km">TOTAL KEWAJIBAN & MODAL</td>
                    <td class="bg-km text-end">
                        {{ number_format($totalKewajiban + $totalModal,0,',','.') }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
