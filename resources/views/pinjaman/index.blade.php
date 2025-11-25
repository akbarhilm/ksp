<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="angsuran" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pinjaman"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container">
            <h2>Daftar Pinjaman</h2>

            <!-- Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('pinjaman.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="id_nasabah" class="form-control" placeholder="ID Nasabah"
                                value="{{ request('id_nasabah') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="nama" class="form-control" placeholder="Nama Nasabah"
                                value="{{ request('nama') }}">
                        </div>
                        
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info">Filter</button>
                            <a href="{{ route('pinjaman.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Pinjaman -->
            <div class="card">
                <div class="card-body overflow-auto">
                    <table class="table table-striped table-bordered align-middle text-sm">
                        <thead class="table-dark">
                            <tr>

                                <th>Nasabah</th>
                                <th>Resort</th>
                                <th>Pinjaman</th>
                                <th>Sisa Pokok</th>
                                <th>Sisa Bunga</th>
                                <th>Status</th>
                                <th>Denda</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pinjaman as $p)
                                <tr>

                                    <td class="">
                                        {{ str_pad($p->id_nasabah, 5, '0', STR_PAD_LEFT) . ' / ' . $p->nasabah->nama ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $p->pengajuan->kode_resort }}
                                    </td>
                                    <td class="text-end font-weight-bolder">{{ number_format($p->total_pinjaman, 0) }}
                                    </td>
                                    <td class="text-end font-weight-bolder">{{ number_format($p->sisa_pokok, 0) }}</td>
                                    <td class="text-end font-weight-bolder">{{ number_format($p->sisa_bunga, 0) }}</td>
                                    @php
                                        $info = \App\Helpers\PinjamanHelper::statusJatuhTempo($p->id_pinjaman);
                                        $denda = \App\Helpers\PinjamanHelper::hitungDenda($p->id_pinjaman);
                                    @endphp

                                    <td>
                                        <span class="badge bg-{{ $info['badge'] }}">
                                            {{ $info['status'] }}
                                        </span>
                                   
                                        <span class="badge bg-{{ $denda['kolekBadge'] }}">
                                            {{ $denda['kolek'] }}
                                        </span>
                                    </td>

                                    <td>
                                        Rp {{ number_format($denda['denda'], 0, ',', '.') }}
                                    </td>

                                    
                                    <td class="text-center pt-1">
                                       
                                        <a href="{{ route('angsuran.index', $p->id_pinjaman) }}"
                                            class="btn btn-sm btn-info btn-sm">
                                            Bayar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data pinjaman</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    @push('js')
        <script>
            //fetchUsers(); // Load all users initially

            // Fetch users (AJAX GET)
            function fetchUsers(query = '') {
                $.ajax({
                    url: "{{ route('rekening.index') }}",
                    method: 'GET',
                    data: {
                        param: query
                    },
                    success: function(data) {
                        console,
                        log(data)
                    }
                });

            }

            // Search as user types
            function cari() {
                let query = $("#param").val()
                fetchUsers(query);
            }
        </script>
    @endpush
</x-layout>
