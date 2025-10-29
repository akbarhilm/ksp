<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="nasabah" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Managemen Nasabah"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Tambah Nasabah</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-2 py-4">
                            <form method="POST" action="{{ route('nasabah.store') }}">

                                @csrf
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label >Nomor Nasabah</label>
                                            <input type="text" class="form-control" name="no_nasabah"  value="{{ old('no_nasabah') }}"/>

                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label >No KTP</label>
                                            <input type="text" class="form-control" name="nik" value="{{ old('nik') }}" />
                                            
                                        </div>
                                         @error('nik')
                                              <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label >Nama</label>
                                            <input type="text" class="form-control" name="nama"  value="{{ old('nama') }}"/>
                                            
                                        </div>
                                         @error('nama')
                                              <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                    </div>
                                

                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-4">
                                        <label >Alamat</label>
                                        <input type="text" class="form-control" name="alamat" value="{{ old('alamat') }}" />
                                        
                                    </div>
                                     @error('alamat')
                                          <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                </div>

                        </div>

                              <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label >No Telp</label>
                                            <input type="text" class="form-control" name="no_telp" value="{{ old('no_telp') }}" />
                                            
                                        </div>
                                         @error('no_telp')
                                              <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                    </div>
                                

                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-4">
                                        <label >Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tgl_lahir" value="{{ old('tgl_lahir') }}" />
                                    </div>
                                </div>

                        </div>

                         <div class="row">
                            <div class="col-md-6">
                                    <div class="input-group input-group-static  mb-4">
                                        <label >Email</label>
                                        <input type="text" class="form-control" name="email" value="{{ old('email') }}" />
                                    </div>
                                </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                   <button class="btn btn-info" type="submit">Simpan</button>
                                </div>
                            </div>
                         </div>



                        {{-- <x-footers.auth></x-footers.auth> --}}
                    </div>
                </div>
            </div>
         </div>
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
