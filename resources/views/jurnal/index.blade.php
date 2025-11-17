<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="jurnal" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Jurnal"></x-navbars.navs.auth>
        <!-- End Navbar -->
       <div class="container mt-4">

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h3 class="mb-3">Jurnal Transaksi</h3>

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
                    <button class="btn btn-primary">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- TABEL JURNAL --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered table-striped mb-4">
                <thead class="table-dark">
                    <tr>
                        <th width="12%">Tanggal</th>
                        <th width="15%">Akun</th>
                        <th>Keterangan</th>
                        <th width="15%" class="text-end">Debit</th>
                        <th width="15%" class="text-end">Kredit</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($jurnal as $row)
                        <tr>
                            <td>{{ $row->tanggal_transaksi }}</td>
                            <td>{{ $row->akun->nama_akun ?? 'Tidak Ada' }}</td>
                            <td>{{ $row->keterangan }}</td>
                            <td class="text-end">{{ $row->v_debet_display }}</td>
                            <td class="text-end">{{ $row->v_kredit_display }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <i>Tidak ada data jurnal untuk filter ini.</i>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
    </main>
    {{-- <x-plugins></x-plugins> --}}

</x-layout>
