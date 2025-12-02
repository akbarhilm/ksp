<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="pengajuan" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pengajuan"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">


                            <table id="tabelNasabah" class="table table-striped table-hover align-middle text-sm"
                                width="100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 5%">Nomor Nasabah</th>
                                        <th>No KTP</th>
                                        <th style="width: 5%">Tgl Lahir</th>
                                        <th>No Telp</th>
                                        <th>Sisa Pokok</th>
                                        <th class="text-center" style="width: 5%">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                            {{-- </div> --}}


                        </div>
                    </div>

                </div>
            </div>
            

    </main>
    @push('js')
        <script>
            $(document).ready(function() {
                $('#tabelNasabah').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('nasabah.datatables') }}",

                    columns: [{
                            data: 'nomor_nasabah',
                            name: 'nomor_nasabah'
                        },
                        {
                            data: 'nik',
                            name: 'nik'
                        },
                        
                        {
                            data: 'tgl_lahir',
                            name: 'tgl_lahir'
                        },
                        {
                            data: 'no_telp',
                            name: 'no_telp'
                        },
                        {
                            data: 'sisa_pokok',
                            name: 'sisa_pokok'
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                    ],

                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        paginate: {
                            previous: "<<",
                            next: ">>"
                        }
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
