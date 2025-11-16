<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="bukubesar" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Buku Besar"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container mt-4">

    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="mb-3">Buku Besar</h3>

            {{-- FILTER FORM --}}
            <form method="GET" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Akun</label>
                    <select name="id_akun" class="form-select">
                        <option value="">-- Semua Akun --</option>
                        @foreach ($akunList as $a)
                            <option value="{{ $a->id_akun }}"
                                {{ $filterAkun == $a->id_akun ? 'selected' : '' }}>
                                {{ $a->nama_akun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="form-control"
                           value="{{ $tanggalAwal }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control"
                           value="{{ $tanggalAkhir }}">
                </div>

                <div class="col-md-2 d-grid">
                    <label class="form-label invisible">Filter</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

            </form>
        </div>
    </div>

    <hr>

    {{-- OUTPUT --}}
    @forelse ($bukuBesar as $akunId => $rows)
        @php
            $akun = $akunList->firstWhere('id_akun', $akunId);
        @endphp

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    Akun: {{ $akun->nama_akun }} (ID: {{ $akunId }})
                </h5>
            </div>

            <div class="card-body p-0">
                <table class="table table-striped table-bordered m-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="15%">Tanggal</th>
                            <th>Keterangan</th>
                            <th width="15%" class="text-end">Debit</th>
                            <th width="15%" class="text-end">Kredit</th>
                            <th width="15%" class="text-end">Saldo</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- SALDO AWAL --}}
                        @if ($rows['saldo_awal'])
                            <tr class="fw-bold bg-light">
                                <td>{{ $rows['saldo_awal']->tanggal_transaksi }}</td>
                                <td>{{ $rows['saldo_awal']->keterangan }}</td>
                                <td class="text-end">0</td>
                                <td class="text-end">0</td>
                                <td class="text-end">{{ number_format($rows['saldo_awal']->saldo, 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        {{-- TRANSAKSI --}}
                        @foreach ($rows['data'] as $row)
                            <tr>
                                <td>{{ $row->tanggal_transaksi }}</td>
                                <td>{{ $row->keterangan }}</td>
                                <td class="text-end">{{ number_format($row->v_debet, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($row->v_kredit, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($row->saldo, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    @empty
        <div class="alert alert-warning mt-3">
            Tidak ada data untuk filter ini.
        </div>
    @endforelse

</div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
