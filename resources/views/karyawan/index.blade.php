<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="karyawan" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Managemen Karyawan"></x-navbars.navs.auth>
        <!-- End Navbar -->
       <div class="container-fluid py-4">
        <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('pinjaman.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="kode_resort" class="form-control" id="filter-id" placeholder="kode resort"
                                value="{{ request('kode_resort') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="nama" class="form-control" id="filter-nama" placeholder="Nama karyawan"
                                value="{{ request('nama') }}">
                        </div>
                        
                       
                    </form>
                </div>
            </div>
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('users.create') }}" class="btn btn-info">
                            <i class="fas fa-plus me-1"></i> Tambah Karyawan
                        </a>
                    </div>
      <table id="nasabahTable"  class="table table-striped table-hover align-middle text-sm" width="100%">
    <thead class="table-dark">
        <tr>
            <th>Nama</th>
            <th>Usernmae</th>
            <th>Role</th>
            <th>Kode Resort</th>

            <th>Aksi</th>
        </tr>
    </thead>
</table>
                </div>
            </div>
        </div>
    </div>
        </div>
    </main>
 @push('js')
<script>
$(function () {
    $('#nasabahTable').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
     
    ajax: {
            url: "{{ route('users.datatableindex') }}",
            data: function(d){
                d.kode_resort = $('#filter-id').val();
                d.nama = $('#filter-nama').val();
            }
        },
        columns: [
            { data: 'nama', name: 'nama' },
            { data: 'username', name: 'username' },
            { data: 'role', name: 'role' },
            { data: 'kode_resort', name: 'kode_resort' },

            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
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
     $('#filter-id,#filter-nama').on('change keyup', function(){
        $('#nasabahTable').DataTable().ajax.reload();
    });
});

function hapusNasabah(id) {
    if (confirm('Hapus karyawan?')) {
        document.getElementById('formDelete' + id).submit();
    }
}

</script>
@endpush
</x-layout>
