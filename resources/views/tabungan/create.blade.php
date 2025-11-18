<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="tabungan" menuParent="simpanan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Tabungan"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Tambah Transaksi Tabungan</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-2 py-4">
                            <form method="POST" action="{{ route('tabungan.store') }}">

                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>No Nasabah</label>
                                            <input type="text" readonly class="form-control" name="nama"
                                                value="{{str_pad($nasabah->id_nasabah,5,'0',STR_PAD_LEFT) }}" 
                                                />

                                        </div>
                                        @error('nama')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Nama Nasabah</label>
                                            <input type="text" readonly class="form-control" name="nama"
                                                value="{{$nasabah->nama }}" 
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
                                            <label>Rekening</label>
                                            <input type="text" id="nama_rekening" class="form-control" readonly name="nama_rekening" value="{{ $rekening[0]->jenis_rekening}}"/>
                                            <input type="hidden" id="id_rekening" name="id_rekening" value="{{ $rekening[0]->id_rekening}}"/>

                                        </div>
                                        @error('jenis')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>

                                     <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Jenis Simpanan</label>
                                           <select class="form-control" name="jenis">
                                            <option value="">--Pilih Jenis Simpanan--</option>
                                            <option value="pokok">Simpanan Pokok</option>
                                            <option value="wajib">Simpanan Wajib</option>
                                            <option value="sukarela">Simpanan Sukarela</option>
                                           </select>
                                        </div>
                                        @error('no_rekening')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Jumlah</label>
                                            <input type="number"  class="form-control" name="v_kredit"
                                                value="{{old('v_kredit') }}" 
                                                />

                                        </div>
                                        @error('v_kredit')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Keterangan</label>
                                            <input type="text"  class="form-control" name="keterangan"
                                                value="{{old('keterangan') }}" 
                                                />

                                        </div>
                                        @error('keterangan')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                 <div class="row">
                            <div class="col-md-10">
                                <div class="input-group input-group-static mb-4">
                                    <button class="btn btn-info" type="submit">Simpan</button>
                                </div>
                            </div>
                             <div class="col-md-2">
                                
                                 <div class="input-group input-group-static mb-4 right">
                                    <a class="btn btn-dark " href="{{ url()->previous() }}">kembali</a>
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
    @push('js')
    <script>
    
    function padWithZeros(number, length) {
        let numString = String(number);
        let padding = "0".repeat(Math.max(0, length - numString.length));
        return padding + numString;
    }
    function generaterekening() {
        
        var jenis_rekening = document.getElementById("jenis_rekening").value;
        var today = new Date();
        var year = today.getFullYear().toString().substr(-2);
       var id_nasabah = '{{ $nasabah->id_nasabah }}';
       var paddedIdNasabah = padWithZeros(id_nasabah, 5);
        var no_rekening = jenis_rekening + year + paddedIdNasabah;
        console.log(no_rekening);
        document.getElementById("no_rekening").value = no_rekening;
         document.getElementById("no_tabungan").value = no_rekening;
        }
</script>
    @endpush
</x-layout>

