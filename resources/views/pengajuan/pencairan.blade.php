<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="cair" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pencairan Pengajuan Pinjaman"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        {{-- <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white mx-3"><strong> Add, Edit, Delete features are not
                                        functional!</strong> This is a<strong> PRO</strong> feature! Click
                                    <strong><a
                                            href="https://www.creative-tim.com/product/material-dashboard-pro-laravel"
                                            target="_blank" class="text-white"><u>here</u> </a></strong>to see
                                    the PRO product!</h6>
                            </div>
                        </div> --}}
                        
                        
                        <!-- <div class="col-md-6">
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-info mb-0" href="{{ route('nasabah.create') }}"><i
                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Tambah Rekening</a>
                        </div>
                        </div> -->
                        <div class="card-body px-0 pb-2">
                            <form  action="{{ route('rekening.cari') }}" method="GET">
                         <div class="row  px-4 py-4">
                            
                            <div class="col-md-3">
                                 
                                <div class="input-group input-group-static mb-4">
                                    <label>Cari Nasabah</label>
                                    <input type="text" name="param" placeholder="No Nasabah / NIK / Nama" class="form-control" />
                                </div>
                                

                                  
                                    <button class="btn bg-gradient-info mb-0" type="submit"><i
                                    class="material-icons text-sm">search</i>&nbsp;&nbsp;cari</button>
                                    
                        </div>
                        </div>
                        </form>
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                No Nasabah
                                            </th>
                                            
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Nama</th>
                                          
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal Pengajuan</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Program</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Jumlah Pengajuan
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Jumlah Approv
                                            </th>
                                            <th class="text-secondary opacity-7" colspan="2"></th>
                                        </tr>
                                    </thead>
                                    @foreach ($pinjaman as $n)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm">{{str_pad($n->rekening[0]->nasabah[0]->id_nasabah,5,'0',STR_PAD_LEFT) }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm"> {{ $n->rekening[0]->nasabah[0]->nama }}</p>
                                                    </div>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $n->tanggal_pengajuan }}</h6>

                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs text-secondary mb-0">{{ $n->program->nama_program }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $n->jumlah_pengajuan }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $n->jumlah_pencairan }}</span>
                                            </td>
                                            
                                            <td class="align-middle">
                                                
                                                <a rel="tooltip" id="appr" class="btn btn-info btn-link" 
                                                    href="{{route('pengajuan.cair', $n->id_pengajuan)}}"
                                                     data-original-title="print" title="print">
                                                   
                                                    <i class="material-icons">print</i>
                                                    <div class="ripple-container"></div>
                        </button>
                        
                                          
                                                {{-- <a class="btn btn-danger" onclick="return confirm('Hapus anggota?')" href="{{route('nasabah.destroy', $n->id_nasabah)}}"><i class="material-icons">close</i>
                                                    <div class="ripple-container"></div></a> --}}
                                               
                                                    <a rel="tooltip" class="btn btn-warning btn-link"
                                                    href="{{ route('pengajuan.decline', $n->id_pengajuan) }}"
                                                    data-original-title="tolak" title="tolak">
                                                    <i class="material-icons">close</i>
                                                    <div class="ripple-container"></div>
                                                </a>
                                               
                                                
                                            </td>
                                           
                                        </tr>
                                    @endforeach

                                    
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <div class="d-flex justify-content-center">
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <x-footers.auth></x-footers.auth> --}}
        </div>

        <!-- Modal -->

    </main>
    <x-plugins></x-plugins>
        @push('js')
<script>

$(document).ready(function() {
    $('#appr').click(function() {
        let id = $(this).data('id');
        
        $.ajax({
            url: '/pengajuan/' + id, // route('nasabah.show', id)
            method: 'GET',
            success: function(data) {
                // isi data ke modal
                $('#v_pengajuan').val(data.jumlah_pengajuan);
                $("#id_pengajuan").val(data.id_pengajuan);

                // tampilkan modal
                $('#exampleModal').modal('show');
            },
            error: function(e) {
                alert('Gagal mengambil data.');
            }
        });
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