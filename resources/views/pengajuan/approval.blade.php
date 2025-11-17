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

                    {{-- Form Pencarian --}}
                    <form action="{{ route('rekening.cari') }}" method="GET">
                        <div class="row g-3 mb-4 align-items-end">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Cari Nasabah</label>
                                <input type="text" name="param" class="form-control"
                                    placeholder="No Nasabah / NIK / Nama">
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-info w-100">
                                    <i class="material-icons text-sm">search</i> Cari
                                </button>
                            </div>

                        </div>
                    </form>

                    {{-- Tabel --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nomor Nasabah</th>
                                    <th>Nama</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Program</th>
                                    <th class="text-center">Jumlah Pengajuan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($pinjaman as $n)
                                <tr>
                                    <td>{{ str_pad($n->rekening[0]->nasabah[0]->id_nasabah, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $n->rekening[0]->nasabah[0]->nama }}</td>
                                    <td class="text-center">{{ $n->tanggal_pengajuan }}</td>
                                    <td class="text-center">{{ $n->program->nama_program }}</td>
                                    <td class="text-center">{{ number_format($n->jumlah_pengajuan, 0) }}</td>
                                    <td class="text-center">{{ $n->status }}</td>

                                    <td class="text-center">

                                        {{-- Tombol Approval (modal) --}}
                                        <button 
                                            class="btn btn-sm btn-success me-1 appr-btn"
                                            data-id="{{ $n->id_pengajuan }}"
                                            data-jumlah="{{ $n->jumlah_pengajuan }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#exampleModal"
                                            title="Setujui">
                                            <i class="material-icons">check</i>
                                        </button>

                                        {{-- Tombol Decline --}}
                                        <a href="{{ route('pengajuan.decline', $n->id_pengajuan) }}"
                                            class="btn btn-sm btn-warning"
                                            title="Tolak">
                                            <i class="material-icons">close</i>
                                        </a>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-center py-3">
                                        {{-- Pagination bisa ditambah di sini --}}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

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
                            <input type="text" id="v_pengajuan" class="form-control" disabled>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah Approve</label>
                            <input type="number" name="jumlah_pencairan" id="v_appr" class="form-control" required>
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
    document.querySelectorAll(".appr-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            let id = this.getAttribute("data-id");
            let jumlah = this.getAttribute("data-jumlah");

            document.getElementById("id_pengajuan").value = id;
            document.getElementById("v_pengajuan").value = jumlah;
            document.getElementById("v_appr").value = jumlah;
        });
    });


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