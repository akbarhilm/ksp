<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="nasabah" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Managemen Anggota"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
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
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm mb-4">
                <div class="card-body overflow-auto">
                    <div class="d-flex justify-content-end mb-3">
                        @if(auth()->user()->role != 'kepalaadmin')
                        <a href="{{ route('nasabah.create') }}" class="btn btn-info">
                            <i class="fas fa-plus me-1"></i> Tambah Anggota
                        </a>
                        @endif
                    </div>
      <table id="nasabahTable"  class="table table-striped table-hover align-middle text-sm" width="100%">
    <thead class="table-dark">
        <tr>
            <th>Nomor</th>
            <th>Resort</th>
            <th>Alamat</th>
            <th>Tanggal Lahir</th>
            <th>Status</th>
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
 $(function(){
    $('#nasabahTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('nasabah.datatablesindex') }}",
            data: function(d){
                d.id_nasabah = $('#filter-id').val();
                d.nama = $('#filter-nama').val();
            }
        },
        columns: [
            { data: 'id_nasabah', name: 'id_nasabah',className:'text-wrap' },
            { data: 'kode_resort', name: 'kode_resort',className:'text-center' },
            { data: 'alamat', name: 'alamat', className:'text-wrap' },
            { data: 'tgl_lahir', name: 'tgl_lahir' },
            { data: 'status', name: 'status' },
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

     // reload saat filter diganti
    $('#filter-id,#filter-nama').on('change keyup', function(){
        $('#nasabahTable').DataTable().ajax.reload();
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


