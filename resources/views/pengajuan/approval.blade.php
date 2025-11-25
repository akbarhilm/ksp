<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="approval" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Approval Pengajuan Pinjaman"></x-navbars.navs.auth>
        <!-- End Navbar -->
         <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
      <table class="table table-striped table-hover align-middle" id="pengajuanTable">
    <thead class="table-dark">
        <tr>
            <th>Nasabah</th>
            <th>Resort</th>
            <th>Tanggal</th>
             <th>Tenor</th>
            <th>Bunga</th>
            <th>Jumlah Pengajuan</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
</table>

</div>
</div>
</div>
</div>
</div>

<!-- ===========================
       MODAL APPROVAL
=========================== -->
<div class="modal fade" id="exampleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('pengajuan.approv') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Approval Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id_pengajuan" id="id_pengajuan">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Jumlah Pengajuan</label>
                            <input type="text" id="v_pengajuan" class="form-control " disabled>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah Approve</label>
                            <input type="text" name="jumlah_pencairan" id="v_appr" class="form-control input-jumlah " required>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Approve</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>

            </form>

        </div>
    </div>
</div>



    </main>
    <x-plugins></x-plugins>
        @push('js')
<script>
    // Handle klik tombol approv

    $(document).ready(function() {

    let table = $('#pengajuanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('pengajuan.datatables') }}",

        columns: [
            { data: 'nasabah', name: 'nasabah' },
           { data: 'resort', name: 'resort',className: 'text-center' },
            { data: 'tanggal', name: 'tanggal',className: 'text-center' },
             { data: 'tenor', name: 'tenor',className: 'text-center' },
             
            { data: 'bunga', name: 'bunga',className: 'text-center' },
            { data: 'jumlah', name: 'jumlah', className: 'text-end' },
            { data: 'status', name: 'status', className: 'text-center' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
        ], language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        paginate: {
                            previous: "<<",
                            next: ">>"
                        }
                    }
    });

    // Ketika tombol approve diklik → isi modal otomatis
    $(document).on('click', '.appr-btn', function() {
        $('#id_pengajuan').val($(this).data('id'));
        $('#v_pengajuan').val($(this).data('jumlah'));
        $('#v_appr').val($(this).data('jumlah'));
    });

});
    // document.querySelectorAll(".appr-btn").forEach(btn => {
    //     btn.addEventListener("click", function () {
    //         let id = this.getAttribute("data-id");
    //         let jumlah = this.getAttribute("data-jumlah");

    //         document.getElementById("id_pengajuan").value = id;
    //         document.getElementById("v_pengajuan").value = jumlah;
    //         document.getElementById("v_appr").value = jumlah;
    //     });
    // });


// $(document).ready(function() {
//     $('#appr').click(function() {
//         let id = $(this).data('id');
        
//         $.ajax({
//             url: '/pengajuan/' + id, // route('nasabah.show', id)
//             method: 'GET',
//             success: function(data) {
//                 // isi data ke modal
//                 $('#v_pengajuan').val(data.jumlah_pengajuan);
//                 $("#id_pengajuan").val(data.id_pengajuan);

//                 // tampilkan modal
//                 $('#exampleModal').modal('show');
//             },
//             error: function(e) {
//                 alert('Gagal mengambil data.');
//             }
//         });
//     });
// });
   
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