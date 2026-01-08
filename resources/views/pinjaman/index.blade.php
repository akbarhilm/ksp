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
                            <input type="text" name="id_nasabah" class="form-control" id="filter-id" placeholder="ID Anggota"
                                value="{{ request('id_nasabah') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="nama" class="form-control" id="filter-nama" placeholder="Nama Anggota"
                                value="{{ request('nama') }}">
                        </div>
                        
                       
                    </form>
                </div>
            </div>

            <!-- Tabel Pinjaman -->
            <div class="card">
                <div class="card-body overflow-auto">
                    <table class="table table-sm table-striped table-bordered" id="table-pinjaman">
    <thead class="table-dark text-sm">
        <tr>
            <th>Anggota</th>
            <th class='d-none'>nama</th>
            <th class='text-sm'>Pencairan</th>
            <th>Resort</th>
            <th>Pinjaman</th>
            <th>Sisa Pokok</th>
            <th>Sisa Bunga</th>
            <th>Status</th>
            <th>Denda</th>
            <th>Aksi</th>
        </tr>
    </thead>
</table>

                </div>
            </div>
        </div>
    </main>
    @push('js')
        <script>
           $(function(){

    $('#table-pinjaman').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
        ajax: {
            url: "{{ route('pinjaman.index') }}",
            data: function(d){
                d.id_nasabah = $('#filter-id').val();
                d.nama = $('#filter-nama').val();
            }
        },
        columns: [
            { data: 'nasabah', name: 'nasabah',className:'text-sm text-wrap' },
            { data: 'nasabah', name: 'nasabah',visible:false },
            { data: 'tgl_cair', name: 'tgl_cair',className:'text-sm text-center'},


            { data: 'resort', name: 'nasabah.kode_resort',className:'text-sm text-center' },
            { data: 'pinjaman', name: 'total_pinjaman', className:'text-end text-sm' },
            { data: 'sisa_pokok', name: 'sisa_pokok', className:'text-end text-sm' },
            { data: 'sisa_bunga', name: 'sisa_bunga', className:'text-end text-sm' },
            { data: 'status', orderable:false, searchable:false,className:'text-sm' },
            { data: 'denda', orderable:false, searchable:false,className:'text-sm' },
            { data: 'aksi', orderable:false, searchable:false, className:'text-center text-sm' }
        ],
          drawCallback: function () {
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    });

    // reload saat filter diganti
    $('#filter-id,#filter-nama').on('change keyup', function(){
        $('#table-pinjaman').DataTable().ajax.reload();
    });

});
        </script>
    @endpush
</x-layout>
