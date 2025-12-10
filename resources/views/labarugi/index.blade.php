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

                <div class="col-md-3 d-grid">
                    <label class="form-label invisible">Filter</label>
                    <button class="btn btn-info">
                        <i class="bi bi-filter"></i> Tampilkan
                    </button>
                </div>
                    <div class="col-md-3 d-grid">
                    <label class="form-label invisible">Cetak</label>
                <a href="{{ url('/laporan/labarugi/pdf?tanggal_awal='.$tanggalAwal.'&tanggal_akhir='.$tanggalAkhir) }}"
   target="_blank"
   class="btn btn-success mb-3">
   Cetak PDF
</a>
                    </div>

            </form>

        </div>
    </div>

    {{-- OUTPUT --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <h5>PENDAPATAN</h5>
<table class="table table-bordered">
<thead>
<tr>
    <th>Kode</th>
    <th>Akun</th>
    <th class="text-end">Jumlah</th>
</tr>
</thead>
<tbody>
@foreach($pendapatanPerAkun as $row)
<tr>
    <td>{{ $row['kode'] }}</td>
    <td>{{ $row['nama'] }}</td>
    <td class="text-end">{{ number_format($row['total'],0,',','.') }}</td>
</tr>
@endforeach
<tr class="fw-bold table-light">
    <td colspan="2">TOTAL PENDAPATAN</td>
    <td class="text-end">{{ number_format($totalPendapatan,0,',','.') }}</td>
</tr>
</tbody>
</table>

<h5>BEBAN / BIAYA</h5>
<table class="table table-bordered">
<thead>
<tr>
    <th>Kode</th>
    <th>Akun</th>
    <th class="text-end">Jumlah</th>
</tr>
</thead>
<tbody>
@foreach($bebanPerAkun as $row)
<tr>
    <td>{{ $row['kode'] }}</td>
    <td>{{ $row['nama'] }}</td>
    <td class="text-end">{{ number_format($row['total'],0,',','.') }}</td>
</tr>
@endforeach
<tr class="fw-bold table-light">
    <td colspan="2">TOTAL BEBAN</td>
    <td class="text-end">{{ number_format($totalBeban,0,',','.') }}</td>
</tr>
</tbody>
</table>

<table class="table table-bordered mt-3">
<tr class="fw-bold">
    <td>LABA / RUGI</td>
    <td class="text-end">
        {{ number_format($laba,0,',','.') }}
    </td>
</tr>
</table>


        </div>
    </div>

</div>

    </main>
</x-layout>

