<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="karyawan" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Managemen Karyawan"></x-navbars.navs.auth>
        <!-- End Navbar -->
       <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('users.create') }}" class="btn btn-info">
                            <i class="fas fa-plus me-1"></i> Tambah Nasabah
                        </a>
                    </div>
      <table id="nasabahTable"  class="table table-striped table-hover align-middle text-sm" width="100%">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Usernmae</th>
            <th>Role</th>
            <th>ID Nasabah</th>
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
$(document).ready(function () {
    $('#nasabahTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
        url: "{{ route('users.datatableindex') }}",
        error: function(xhr, error, thrown) {
            console.log("Terjadi error saat mengambil data:", error);
        }
    },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'nama', name: 'nama' },
            { data: 'username', name: 'username' },
            { data: 'role', name: 'role' },
            { data: 'id_nasabah', name: 'id_nasabah' },
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
});

function hapusNasabah(id) {
    if (confirm('Hapus karyawan?')) {
        document.getElementById('formDelete' + id).submit();
    }
}

</script>
@endpush
</x-layout>
