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
                                <h6 class="text-white text-capitalize ps-3">Tambah Rekening</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-2 py-4">
                            <form method="POST" action="{{ route('rekening.store') }}">

                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Nama Nasabah</label>
                                            <input type="text" readonly class="form-control" name="nama"
                                                value="{{ $nasabah->nama }}" 
                                                />

                                        </div>
                                        @error('nama')
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
                                            <input type="text" class="form-control" name="no_rekening"
                                                value="{{ old('no_rekening') }}" />

                                        </div>
                                        @error('no_rekening')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>



                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>No Tabungan</label>
                                            <input type="text" class="form-control" name="no_tabungan"
                                                value="{{ old('no_tabungan') }}" />
                                        </div>
                                        @error('no_tabungan')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Nama Tabungan</label>
                                            <input type="text" class="form-control" name="nama_tabungan"
                                                value="{{ old('nama_tabungan') }}" />
                                        </div>
                                        @error('nama_tabungan')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Bunga</label>
                                            <select class="form-control" name="id_bunga">
                                                <option value="">--Pilih Bunga--</option>
                                                @foreach ($bunga as $b)
                                                    <option value="{{ $b->id_bunga }}">
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
                                    <select class="form-control" name="kode_insentif">
                                        <option value="">--Pilih Insentif--</option>
                                        <option value="0">Tidak Ada Insentif</option>
                                        <option value="1">DAPAT PBTV</option>

                                    </select>

                                </div>
                                @error('id_insentif')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Kode Resort</label>
                                    <select class="form-control" name="kode_resort">
                                        <option value="">--Pilih Resort--</option>
                                        <option value="1">SK NIP TASPEN</option>
                                        <option value="2">SERTIFIKAT</option>
                                        <option value="3">KARIP</option>
                                        <option value="4">BPKB</option>
                                        <option value="5">ATM</option>
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
                                        value="{{ old('tabungan_wajib') }}" />
                                </div>
                                @error('tabungan_wajib')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Tabungan Rutin</label>
                                    <input type="text" class="form-control" name="tabungan_rutin"
                                        value="{{ old('tabungan_rutin') }}"/>
                                </div>
                                @error('tabungan_rutin')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
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
@push('scripts')
<script>
    function numf() {
        console.log("ads");
       // obj.value = obj.value.replace(/\D/g, "");
        }
</script>
    @endpush