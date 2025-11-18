<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="nasabah" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Managemen Nasabah"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('nasabah.create') }}" class="btn btn-info">
                            <i class="fas fa-plus me-1"></i> Tambah Nasabah
                        </a>
                    </div>
      <table id="nasabahTable"  class="table table-striped table-hover align-middle text-sm" width="100%">
    <thead class="table-dark">
        <tr>
            <th>Nomor</th>
            <th>No KTP</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Tanggal Lahir</th>
            <th>No Telp</th>
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
    <!-- <x-plugins></x-plugins> -->
  @push('js')
<script>
$(document).ready(function () {
    $('#nasabahTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('nasabah.datatablesindex') }}",
        columns: [
            { data: 'id_nasabah', name: 'id_nasabah' },
            { data: 'nik', name: 'nik' },
            { data: 'nama', name: 'nama' },
            { data: 'alamat', name: 'alamat' },
            { data: 'tgl_lahir', name: 'tgl_lahir' },
            { data: 'no_telp', name: 'no_telp' },
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
    if (confirm('Hapus anggota?')) {
        document.getElementById('formDelete' + id).submit();
    }
}

</script>
@endpush
</x-layout>


