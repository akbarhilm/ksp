<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="cair" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pencairan Pengajuan Pinjaman"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">

                            {{-- Form Pencarian --}}
                            <form action="{{ route('rekening.cari') }}" method="GET">
                                <div class="row g-3 mb-4 align-items-end">

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Cari Nasabah</label>
                                        <input type="text" name="param" class="form-control"
                                            placeholder="No Nasabah / NIK / Nama">
                                    </div>

                                    <div class="col-md-2">
                                        <button class="btn btn-info w-100">
                                            <i class="material-icons text-sm">search</i> Cari
                                        </button>
                                    </div>

                                </div>
                            </form>

                            {{-- Tabel Data --}}
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center text-sm">Nasabah</th>
                                           <th class="text-center text-sm">Jenis</th>

                                            <th class="text-center text-sm">Tanggal Pengajuan</th>
                                            <th class="text-center text-sm">Jumlah Approve</th>
                                            <th class="text-center text-sm">Simpanan Pokok</th>
                                            <th class="text-center text-sm">Provisi</th>
                                            <th class="text-center text-sm">Asuransi</th>
                                            <th class="text-center text-sm">Sisa Pokok Pinjaman</th>
                                            <th class="text-center text-sm">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($pinjaman as $n)
                                            @php
                                                $pinjamanAktif = $n->rekening[0]->nasabah[0]->pinjaman->where('status','aktif')->first();
                                            @endphp
                                            <tr>
                                                <td class="text-center text-sm">
                                                    {{ str_pad($n->rekening[0]->nasabah[0]->id_nasabah, 5, '0', STR_PAD_LEFT) . ' / ' . $n->rekening[0]->nasabah[0]->nama }}
                                                </td>
                                                 <td class="text-center text-sm">
                                                    {{ $n->jenis }}
                                                </td>


                                                <td class="text-center text-sm">{{ $n->tanggal_pengajuan }}</td>




                                                <td class="text-end text-sm">
                                                    {{ number_format($n->jumlah_pencairan, 0) }}
                                                </td>
                                                <td class="text-end text-sm">
                                                    {{ number_format($n->simpanan_pokok, 0) }}
                                                </td>
                                                <td class="text-end text-sm">
                                                    {{ number_format($n->admin, 0) }}
                                                </td>
                                                <td class="text-end text-sm">
                                                    {{ number_format($n->asuransi, 0) }}
                                                </td>
                                                <td class="text-end text-sm">
                                                    {{ $pinjamanAktif ? number_format($pinjamanAktif->sisa_pokok + $pinjamanAktif->sisa_bunga,0,',','.') : '0' }}
                                                </td>

                                                <td class="text-center text-sm">
  <a href="{{ route('pengajuan.detail.pencairan', $n->id_pengajuan) }}"
                                                        class="btn btn-sm btn-info" title="lihat detail">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    {{-- <button id="btnCair" class="btn btn-sm btn-info"
                                                        data-id="{{ $n->id_pengajuan }}" title="Print">
                                                        <i class="material-icons">print</i>
                                                    </button> --}}

                                                    <a href="{{ route('pengajuan.decline', $n->id_pengajuan) }}"
                                                        class="btn btn-sm btn-warning" title="Tolak">
                                                        <i class="material-icons">close</i>
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                {{-- Pagination jika diperlukan --}}
                                                {{-- {{ $pinjaman->links() }} --}}
                                            </td>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Modal -->

    </main>
    @push('js')
        <script>
            $('#btnCair').click(function() {
                let id = $(this).data('id');

                $.get('/pengajuan/cair/' + id, function(res) {
                    if (res.success) {
                        // buka PDF di tab baru
                        window.open(res.pdf_url, '_blank');

                        // refresh atau redirect halaman
                        location.reload(); // atau window.location.href = '/pengajuan';
                    }
                });
            });




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
