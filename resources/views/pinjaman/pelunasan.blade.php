<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="pelunasan" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pelunasan"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container">
            <h2>Daftar Pinjaman</h2>

            <!-- Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('pelunasan.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="id_nasabah" id="filter-id" class="form-control" placeholder="ID Nasabah"
                                value="{{ request('id_nasabah') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="nama" class="form-control" id="filter-nama" placeholder="Nama Nasabah"
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
            <th>Nasabah</th>
            <th>Resort</th>
            <th>Pinjaman</th>
            <th>Sisa Pokok</th>
            <th>Sisa Bunga</th>
           
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
        ajax: {
            url: "{{ route('pelunasan.index') }}",
            data: function(d){
                d.id_nasabah = $('#filter-id').val();
                d.nama = $('#filter-nama').val();
            }
        },
        columns: [
            { data: 'nasabah', name: 'nasabah',className:'text-sm' },
            { data: 'resort', name: 'pengajuan.kode_resort',className:'text-sm' },
            { data: 'pinjaman', name: 'total_pinjaman', className:'text-end text-sm' },
            { data: 'sisa_pokok', name: 'sisa_pokok', className:'text-end text-sm' },
            { data: 'sisa_bunga', name: 'sisa_bunga', className:'text-end text-sm' },
          
            { data: 'aksi', orderable:false, searchable:false, className:'text-center text-sm' }
        ],
          
    });

    // reload saat filter diganti
    $('#filter-id,#filter-nama').on('change keyup', function(){
        $('#table-pinjaman').DataTable().ajax.reload();
    });
            });
        </script>
    @endpush
</x-layout>
