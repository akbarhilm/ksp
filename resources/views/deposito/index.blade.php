<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="deposito" menuParent="simpanan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Deposito"></x-navbars.navs.auth>
        <!-- End Navbar -->
           <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm mb-4">
                <div class="card-body">
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
    <x-plugins></x-plugins>
  @push('js')
<script>
$(document).ready(function () {
    $('#nasabahTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('deposito.datatablesdeposito') }}",
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