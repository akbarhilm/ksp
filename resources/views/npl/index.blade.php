<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="neraca" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Laporan Neraca"></x-navbars.navs.auth>
        <!-- End Navbar -->
<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h3 class="mb-3">NPL ({{ date('d-m-Y') }})</h3>

            {{-- Filter tanggal --}}
            
                  <a href="{{ url('/laporan/neraca/pdf?tanggal=') }}"
   class="btn btn-success"
   target="_blank">
   Cetak PDF
</a>
 </div>
           

            {{-- Flex container untuk dua kartu --}}
            <div class="d-flex gap-3">
                {{-- ASET --}}
                <div class="card flex-fill">
                    <div class="card-header bg-info text-white">Aset</div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-bordered m-0">
                            <tbody>
                                @php
                                    $totalAset = 0;
                                    $asetCount = count($neraca['Aset']);
                                    $kmCount = count($neraca['Kewajiban']) + count($neraca['Modal']);
                                    $maxRows = max($asetCount, $kmCount);
                                @endphp
                                @for($i = 0; $i < $maxRows; $i++)
                                    @if(isset($neraca['Aset'][$i]))
                                        <tr>
                                            <td>{{ $neraca['Aset'][$i]['nama'] }}</td>
                                            <td class="text-end">{{ number_format($neraca['Aset'][$i]['saldo'],0,',','.') }}</td>
                                        </tr>
                                        @php $totalAset += $neraca['Aset'][$i]['saldo']; @endphp
                                    @else
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    @endif
                                @endfor
                                <tr class="fw-bold bg-light text-white">
                                    <td class="bg-info">Total Aset</td>
                                    <td class="text-end bg-info">{{ number_format($totalAset,0,',','.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- KEWAJIBAN & MODAL --}}
                <div class="card flex-fill">
                    <div class="card-header bg-success text-white">Kewajiban & Modal</div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-bordered m-0">
                            <tbody>
                                @php
                                    $totalKewajiban = 0;
                                    $totalModal = 0;
                                @endphp
                                @for($i = 0; $i < $maxRows; $i++)
                                    @php
                                        // Ambil item kewajiban atau modal sesuai index
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
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    @endif
                                @endfor
                                <tr class="fw-bold bg-light text-white">
                                    <td class="bg-success">Total Kewajiban & Modal</td>
                                    <td class="text-end bg-success">{{ number_format($totalKewajiban + $totalModal,0,',','.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> {{-- end flex container --}}

        </div>
    </div>
</div>


    </main>
</x-layout>
