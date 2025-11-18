<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="neraca" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Laporan Neraca"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h3 class="mb-3">Neraca ({{ date('d-m-Y', strtotime($tanggal)) }})</h3>

            {{-- Filter tanggal --}}
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                </div>
                <div class="col-md-2 d-grid">
                    <label class="form-label invisible">Filter</label>
                    <button class="btn btn-info">Tampilkan</button>
                </div>
            </form>

            {{-- Tabel gabungan dengan warna --}}
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="bg-info text-white" colspan="2">Aset</th>
                            <th class="bg-success text-white" colspan="2">Kewajiban & Modal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $maxRows = max(count($neraca['Aset']), count($neraca['Kewajiban']) + count($neraca['Modal']));
                            $totalAset = 0;
                            $totalKewajiban = 0;
                            $totalModal = 0;
                        @endphp

                        @for($i = 0; $i < $maxRows; $i++)
                            <tr>
                                {{-- Aset --}}
                                @if(isset($neraca['Aset'][$i]))
                                    <td class="bg-light">{{ $neraca['Aset'][$i]['nama'] }}</td>
                                    <td class="bg-light text-end">{{ number_format($neraca['Aset'][$i]['saldo'],0,',','.') }}</td>
                                    @php $totalAset += $neraca['Aset'][$i]['saldo']; @endphp
                                @else
                                    <td class="bg-light">&nbsp;</td>
                                    <td class="bg-light">&nbsp;</td>
                                @endif

                                {{-- Kewajiban & Modal --}}
                                @php
                                    $kmIndex = $i;
                                    if($kmIndex < count($neraca['Kewajiban'])) {
                                        $kmItem = $neraca['Kewajiban'][$kmIndex];
                                        $totalKewajiban += $kmItem['saldo'];
                                    } elseif($kmIndex - count($neraca['Kewajiban']) < count($neraca['Modal'])) {
                                        $kmItem = $neraca['Modal'][$kmIndex - count($neraca['Kewajiban'])];
                                        $totalModal += $kmItem['saldo'];
                                    } else {
                                        $kmItem = null;
                                    }
                                @endphp

                                @if($kmItem)
                                    <td class="bg-light">{{ $kmItem['nama'] }}</td>
                                    <td class="bg-light text-end">{{ number_format($kmItem['saldo'],0,',','.') }}</td>
                                @else
                                    <td class="bg-light">&nbsp;</td>
                                    <td class="bg-light">&nbsp;</td>
                                @endif
                            </tr>
                        @endfor

                        {{-- Total --}}
                        <tr class="fw-bold text-white">
                            <td class="bg-info">Total Aset</td>
                            <td class="bg-info text-end">{{ number_format($totalAset,0,',','.') }}</td>
                            <td class="bg-success">Total Kewajiban & Modal</td>
                            <td class="bg-success text-end">{{ number_format($totalKewajiban + $totalModal,0,',','.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

    </main>
</x-layout>
