<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="tabungan" menuParent="simpanan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Tabungan"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm mb-4">
                <div class="card-body">
      <table id="rekeningTable"  class="table table-striped table-hover align-middle text-sm" width="100%">
    <thead class="table-dark">
        <tr>
            <th class='w-10'>Nasabah</th>
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
        @push('js')
<script>

    $(document).ready(function () {
    $('#rekeningTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url : "{{ route('tabungan.datatablestabungan') }}",
            error:function (xhr, error, thrown) {
                console.log(xhr.responseText);
            }
        },
        columns: [
            { data: 'id_nasabah', name: 'id_nasabah', className: 'w-10' },
            { data: 'nik', name: 'nik',className: 'w-15' },
            { data: 'alamat', name: 'alamat',className: 'text-wrap w-30'  },
            { data: 'tgl_lahir', name: 'tgl_lahir',className: 'w-5'  },
            { data: 'no_telp', name: 'no_telp',className: 'w-10'  },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false,className: 'w-15'  },
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