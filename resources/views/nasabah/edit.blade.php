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
                                <h6 class="text-white text-capitalize ps-3">Edit Nasabah</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-2 py-4">
                            <div class="card-body px-4 pb-2 py-4">
                            <form  action="{{ route('nasabah.update', $nasabah->id_nasabah) }}" method="POST">

                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>No KTP</label>
                                            <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control" name="nik"
                                                value="{{$nasabah->nik}}" />

                                        </div>
                                        @error('nik')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" name="nama"
                                                value="{{ $nasabah->nama }}" />

                                        </div>
                                        @error('nama')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Tanggal Lahir</label>
                                            <input type="date" class="form-control" name="tgl_lahir"
                                                value="{{ $nasabah->tgl_lahir }}" />
                                        </div>
                                        @error('tgl_lahir')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Alamat</label>
                                            <input type="text" class="form-control" name="alamat"
                                                value="{{ $nasabah->alamat }}" />
                                        </div>
                                        @error('alamat')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>No Telp</label>
                                            <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control" name="no_telp"
                                                value="{{ $nasabah->no_telp }}" />

                                        </div>
                                        @error('no_telp')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Nama Suami / Istri</label>
                                            <input type="text" class="form-control" name="nama_suami_istri"
                                                value="{{ $nasabah->nama_suami_istri }}" />
                                        </div>
                                        @error('nama_suami_istri')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Pekerjaan</label>
                                            <input type="text" class="form-control" name="pekerjaan"
                                                value="{{ $nasabah->pekerjaan }}" />
                                        </div>
                                        @error('pekerjaan')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Sektor Ekonomi</label>
                                            <select class="form-control" name="sektor_ekonomi" value="{{ $nasabah->sektor_ekonomi }}">
                                                <option value="">--Pilih Sektor Ekonomi--</option>
                                                <option value="PNS" {{$nasabah->sektor_ekonomi == 'PNS'? 'selected':''}}>PNS</option>
                                                <option value="Wiraswasta" {{$nasabah->sektor_ekonomi == 'Wiraswasta'? 'selected':''}}>Wiraswasta</option>
                                                <option value="Swasta" {{$nasabah->sektor_ekonomi == 'Swasta'? 'selected':''}}>Swasta</option>
                                                <option value="Lainnya" {{$nasabah->sektor_ekonomi == 'Lainnya'? 'selected':''}}>Lainnya</option>
                                            </select>
                                        </div>
                                        @error('sektor_ekonomi')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2 input-group input-group-static mb-4">
                                            <button class="btn btn-info" type="submit">Simpan</button>
                                       
                                            <a class="btn btn-dark btn-link " href="{{ url()->previous() }}">kembali</a>
                                        </div>
                                    </div>
                              
                                </div>

                            </form>

                                {{-- <x-footers.auth></x-footers.auth> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Rekening Nasabah</h6>
                            </div>
                        </div>
                        @foreach ($rekening as $rek )
                            
                        
                        <div class="card-body px-4 pb-2 py-4">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Rekening</label>
                                            <select class="form-control" id="jenis_rekening" disabled name="jenis_rekening" onchange="generaterekening()">
                                                <option value="">--Pilih Rekening--</option>
                                               <option value="Tabungan" {{$rek->jenis_rekening == 'Tabungan'? 'selected':''}}>REKENING TABUNGAN</option>
                                               <option value="Deposito" {{$rek->jenis_rekening == 'Deposito'? 'selected':''}}>REKENING DEPOSITO</option>
                                               <option value="Pinjaman" {{$rek->jenis_rekening == 'Pinjaman'? 'selected':''}}>REKENING Pinjaman</option>
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
                                            <input type="text" id="no_rekening" readonly class="form-control" name="no_rekening"
                                                value="{{ $rek->no_rekening }}"  />

                                        </div>
                                        @error('no_rekening')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="row">
                                         <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            @if($rek->jenis_rekening == 'Tabungan' && $rek->status == 'aktif')
                                            <a href="{{route('tabungan.show', $nasabah->id_nasabah)}}" class="btn btn-info btn-link">Lihat</a>
                                            @elseif($rek->jenis_rekening == 'Deposito'  && $rek->status == 'aktif')
                                            <a href="{{route('deposito.show', $nasabah->id_nasabah)}}" class="btn btn-info btn-link">Lihat</a>
                                             @elseif($rek->jenis_rekening == 'Pinjaman'  && $rek->status == 'aktif')
                                            <a href="{{}}" class="btn btn-info btn-link">Lihat</a>
                                            @else
                                            <span  class="btn btn-warning btn-link">Belum Aktif</span>
                                            @endif

                                    </div>
                                    </div>
                                    </div>

                                    
                              
                        
                        
                        


                                </div>
                        {{-- <x-footers.auth></x-footers.auth> --}}
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
