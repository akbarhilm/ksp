<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="labarugi" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Laba / Rugi"></x-navbars.navs.auth>
<div class="container mt-4">

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h3 class="mb-3">Laporan Laba / Rugi</h3>

            {{-- FORM FILTER --}}
            <form class="row g-3" method="GET">

                <div class="col-md-4">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="form-control"
                        value="{{ $tanggalAwal }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control"
                        value="{{ $tanggalAkhir }}">
                </div>

                <div class="col-md-4 d-grid">
                    <label class="form-label invisible">Filter</label>
                    <button class="btn btn-info">
                        <i class="bi bi-filter"></i> Tampilkan
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- OUTPUT --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <h4 class="mb-4 text-center fw-bold">Laporan Laba Rugi</h4>
            <p class="text-center">
                Periode:
                <strong>{{ $tanggalAwal ?? '-' }} s/d {{ $tanggalAkhir ?? '-' }}</strong>
            </p>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Keterangan</th>
                        <th width="25%" class="text-end">Jumlah</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="table-success">
                        <td><strong>Total Pendapatan</strong></td>
                        <td class="text-end">{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>

                    <tr class="table-danger">
                        <td><strong>Total Beban</strong></td>
                        <td class="text-end">{{ number_format($totalBeban, 0, ',', '.') }}</td>
                    </tr>

                    <tr class="table-primary fw-bold">
                        <td><strong>Laba Bersih</strong></td>
                        <td class="text-end">{{ number_format($laba, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

</div>

    </main>
</x-layout>

