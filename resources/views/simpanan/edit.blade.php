<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="rekening" menuParent="admin"></x-navbars.sidebar>
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
                                            <input type="text" class="form-control" name="nik"
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
                                            <input type="text" class="form-control" name="no_telp"
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
                                                <option value="Pertanian" {{$nasabah->sektor_ekonomi == 'Pertanian'? 'selected':''}}>Pertanian</option>
                                                <option value="Perdagangan" {{$nasabah->sektor_ekonomi == 'Perdagangan'? 'selected':''}}>Perdagangan</option>
                                                <option value="Industri" {{$nasabah->sektor_ekonomi == 'Industri'? 'selected':''}}>Industri</option>
                                                <option value="Jasa" {{$nasabah->sektor_ekonomi == 'Jasa'? 'selected':''}}>Jasa</option>
                                                <option value="Lainnya" {{$nasabah->sektor_ekonomi == 'Lainnya'? 'selected':''}}>Lainnya</option>
                                            </select>
                                        </div>
                                        @error('sektor_ekonomi')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <button class="btn btn-info" type="submit">Simpan</button>
                                        </div>
                                    </div>
                              
                                 <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <a class="btn btn-success" href="{{ route('rekening.create',['id_nasabah' => $nasabah->id_nasabah]) }}">Tambah Rekening</a>
                                        </div>
                                    </div>
                                </div> -->

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
                           <form>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Rekening</label>
                                            <select class="form-control" id="jenis_rekening" disabled name="jenis_rekening" onchange="generaterekening()">
                                                <option value="">--Pilih Rekening--</option>
                                               <option value="1" {{$rek->jenis_rekening == '1'? 'selected':''}}>REKENING TABUNGAN</option>
                                               <option value="2" {{$rek->jenis_rekening == '2'? 'selected':''}}>REKENING DEPOSITO</option>
                                               <option value="3" {{$rek->jenis_rekening == '3'? 'selected':''}}>REKENING Pinjaman</option>
                                            </select>

                                        </div>
                                        @error('jenis_rekening')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
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



                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>No Tabungan</label>
                                            <input type="text" id="no_tabungan" class="form-control" readonly name="no_tabungan"
                                                value="{{ $rek->no_tabungan }}" />
                                        </div>
                                        @error('no_tabungan')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Bunga</label>
                                            <select class="form-control" disabled name="id_bunga">
                                                <option value="">--Pilih Bunga--</option>
                                                @foreach ($bunga as $b)
                                                    <option value="{{ $b->id_bunga }}" {{$rek->id_bunga == $b->id_bunga ? 'selected':''}}>
                                                        {{ $b->nama_bunga }} - {{ $b->suku_bunga1 }}%
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('id_bunga')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Kode Insentif</label>
                                    <select class="form-control" disabled name="kode_insentif">
                                        <option value="">--Pilih Insentif--</option>
                                        <option value="0" {{$rek->kode_insentif == '0'? 'selected':''}}>Tidak Ada Insentif</option>
                                        <option value="1" {{$rek->kode_insentif == '1'? 'selected':''}}>DAPAT PBTV</option>

                                    </select>

                                </div>
                                @error('id_insentif')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Kode Resort</label>
                                    <select class="form-control" disabled name="kode_resort">
                                        <option value="">--Pilih Resort--</option>
                                        <option value="1" {{$rek->kode_resort == '1'? 'selected':''}}>SK NIP TASPEN</option>
                                        <option value="2" {{$rek->kode_resort == '2'? 'selected':''}}>SERTIFIKAT</option>
                                        <option value="3" {{$rek->kode_resort == '3'? 'selected':''}}>KARIP</option>
                                        <option value="4" {{$rek->kode_resort == '4'? 'selected':''}}>BPKB</option>
                                        <option value="5" {{$rek->kode_resort == '5'? 'selected':''}}>ATM</option>
                                    </select>
                                </div>
                                @error('id_resort')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Tabungan Wajib</label>
                                    <input type="text" class="form-control" name="tabungan_wajib"
                                        value="{{ $rek->tabungan_wajib }}" />
                                </div>
                                @error('tabungan_wajib')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Tabungan Rutin</label>
                                    <input type="text" class="form-control" name="tabungan_rutin"
                                        value="{{ $rek->tabungan_rutin }}"/>
                                </div>
                                @error('tabungan_rutin')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
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
