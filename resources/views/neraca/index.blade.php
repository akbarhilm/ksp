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

            <div class="row">
                {{-- ASET --}}
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-info text-white">Aset</div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-bordered m-0">
                                <tbody>
                                    @php $totalAset = 0; @endphp
                                    @foreach($neraca['Aset'] as $item)
                                        <tr>
                                            <td>{{ $item['nama'] }}</td>
                                            <td class="text-end">{{ number_format($item['saldo'], 0, ',', '.') }}</td>
                                        </tr>
                                        @php $totalAset += $item['saldo']; @endphp
                                    @endforeach
                                    <tr class="fw-bold bg-light">
                                        <td>Total Aset</td>
                                        <td class="text-end">{{ number_format($totalAset, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- KEWAJIBAN + MODAL --}}
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-success text-white">Kewajiban & Modal</div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-bordered m-0">
                                <tbody>
                                    @php
                                        $totalKewajiban = 0;
                                        $totalModal = 0;
                                    @endphp

                                    @foreach($neraca['Kewajiban'] as $item)
                                        <tr>
                                            <td>{{ $item['nama'] }}</td>
                                            <td class="text-end">{{ number_format($item['saldo'], 0, ',', '.') }}</td>
                                        </tr>
                                        @php $totalKewajiban += $item['saldo']; @endphp
                                    @endforeach

                                    @foreach($neraca['Modal'] as $item)
                                        <tr>
                                            <td>{{ $item['nama'] }}</td>
                                            <td class="text-end">{{ number_format($item['saldo'], 0, ',', '.') }}</td>
                                        </tr>
                                        @php $totalModal += $item['saldo']; @endphp
                                    @endforeach

                                    <tr class="fw-bold bg-light">
                                        <td>Total Kewajiban & Modal</td>
                                        <td class="text-end">{{ number_format($totalKewajiban + $totalModal, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
