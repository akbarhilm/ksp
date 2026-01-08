<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="npl" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Laporan Neraca"></x-navbars.navs.auth>
        <!-- End Navbar -->
<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4>Laporan NPL per Penanggung Jawab (Resort)</h4>
            <p>Tanggal: {{ $tanggal }}</p>

            @foreach($dataResort as $resort => $row)

            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <strong>Resort : {{ $resort }}</strong>

                    {{-- TOGGLE BUTTON --}}
                    <button class="btn btn-sm btn-outline-primary"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#detail-{{ Str::slug($resort) }}">
                        Detail Anggota
                    </button>
                </div>

                <div class="card-body">

                    {{-- RINGKASAN --}}
                    <table class="table table-bordered mb-3">
                        <tr>
                            <th>Total Kredit</th>
                            <td class="text-end">
                                Rp {{ number_format($row['total_kredit'],0,',','.') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Total NPL</th>
                            <td class="text-end">
                                Rp {{ number_format($row['total_npl'],0,',','.') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Rasio NPL</th>
                            <td>
                                <span class="badge bg-{{ $row['rasio'] > 5 ? 'danger' : 'success' }}">
                                    {{ $row['rasio'] }} %
                                </span>
                            </td>
                        </tr>
                    </table>

                    {{-- COLLAPSE DETAIL --}}
                    <div class="collapse" id="detail-{{ Str::slug($resort) }}">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Anggota</th>
                                    <th class="text-center">Tanggal Cair</th>
                                    <th class="text-center">Jatuh Tempo Terakhir</th>
                                    <th class="text-end">Sisa Pokok</th>
                                    <th class="text-center">Hari Tunggakan</th>
                                    <th class="text-center">Kolek</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($row['detail'] as $d)
                                <tr>
                                    <td class="text-wrap">{{ $d['nasabah'] }}</td>
                                    <td class="text-center">{{ $d['tgl_cair'] }}</td>
                                    <td class="text-center">{{ substr($d['cicilan_terakhir'],0,10) }}</td>
                                    <td class="text-end">
                                        {{ number_format($d['sisa'],0,',','.') }}
                                    </td>
                                    <td class="text-center">{{ $d['hari'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ in_array($d['kolek'],['C3','C4','C5']) ? 'danger' : 'success' }}">
                                            {{ $d['kolek'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            @endforeach

        </div>
    </div>
</div>


    </main>
</x-layout>
