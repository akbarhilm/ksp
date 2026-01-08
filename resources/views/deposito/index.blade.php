<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="deposito" menuParent="simpanan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Deposito"></x-navbars.navs.auth>
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
      <table id="nasabahTable"  class="table table-striped table-hover align-middle text-sm" width="100%">
    <thead class="table-dark">
        <tr>
            <th>Anggota</th>
            <th>No KTP</th>
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
    <x-plugins></x-plugins>
  @push('js')
<script>
$(function () {
    $('#nasabahTable').DataTable({
        processing: true,
        serverSide: true,
        searching:false,
         ajax: {
            url: "{{ route('deposito.datatablesdeposito') }}",
            data: function(d){
                d.id_nasabah = $('#filter-id').val();
                d.nama = $('#filter-nama').val();
            }
        },
        columns: [
            { data: 'id_nasabah', name: 'id_nasabah' },
            { data: 'nik', name: 'nik' },
            { data: 'alamat', name: 'alamat',className: 'text-wrap'  },
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
         $('#filter-id,#filter-nama').on('change keyup', function(){
        $('#nasabahTable').DataTable().ajax.reload();
    });
});
   
            //fetchUsers(); // Load all users initially
           
            // Fetch users (AJAX GET)
            function fetchUsers(query = '') {
                $.ajax({
                    url: "{{ route('rekening.index') }}",
                    method: 'GET',
                    data: { param: query },
                    success: function(data) {
                        console,log(data)
                    }
                            });
                       
            }
            
            // Search as user types
           function cari() {
                let query =  $("#param").val()
                fetchUsers(query);
            }
        
  </script>
   @endpush
</x-layout>