<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="rekening" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Managemen Anggota"></x-navbars.navs.auth>
        <!-- End Navbar -->
        

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Rekening Anggota</h6>
                            </div>
                        </div>
                        @foreach ($rekening as $rek )
                            
                        
                        <div class="card-body px-4 pb-2 py-4">
                           <form>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Rekening</label>
                                            <select class="form-control" id="jenis_rekening" disabled name="jenis_rekening" onchange="generaterekening()">
                                                <option value="">--Pilih Rekening--</option>
                                               <option value="1" {{$rek->jenis_rekening == 'Tabungan'? 'selected':''}}>REKENING TABUNGAN</option>
                                               <option value="2" {{$rek->jenis_rekening == 'Deposito'? 'selected':''}}>REKENING DEPOSITO</option>
                                               <option value="3" {{$rek->jenis_rekening == 'Pinjaman'? 'selected':''}}>REKENING Pinjaman</option>
                                            </select>

                                        </div>
                                        @error('jenis_rekening')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                              
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>No Rekening</label>
                                            <input type='hidden' name='id_nasabah'
                                                value='{{ $nasabah->id_nasabah }}' 
                                                />
                                                <input type='hidden' name='id_rekening'
                                                value='{{ $rek->id_rekening }}' 
                                                />
                                            <input type="text" id="no_rekening" readonly class="form-control" name="no_rekening"
                                                value="{{ $rek->no_rekening }}"  />

                                        </div>
                                        @error('no_rekening')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>



                                   
                               
                        
                        <div class="row">
                            <div class="col-md-2 ">
                                        <div class="input-group input-group-static mb-4 right">
                                            @if($rek->status == 'nonaktif')
                                            <a class="btn btn-info btn-link " href="{{route('rekening.aktif',['id_rekening'=>$rek->id_rekening]) }}">Aktifkan</a>
                                            @else
                                            <span class="badge bg-success">
                                    {{ ucfirst($rek->status) }}
                                </span>
                                @endif
                                        </div>
                                    </div>
                        </div>
                        



                        {{-- <x-footers.auth></x-footers.auth> --}}
                        </form>
                    </div>
                    <hr class="border-top border-4 border-dark">
                        
                    @endforeach
                </div>
            </div>
        </div>
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
