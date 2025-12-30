<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="tabungan" menuParent="simpanan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Tabungan"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card shadow-sm">
                <div class="card-header bg-success ">
                    <h5 class="mb-0 text-white">Form Penarikan Tabungan</h5>
                </div>

                <div class="card-body">

                    <form action="{{ route('tabungan.penarikan.store') }}" method="POST">
                        @csrf

                        {{-- Data Nasabah --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">No. Nasabah</label>
                                <input type="text" class="form-control" readonly value="{{ str_pad($nasabah->id_nasabah,5,'0',STR_PAD_LEFT) }}">
                                <input type="hidden" name='id_nasabah' class="form-control" readonly value="{{ str_pad($nasabah->id_nasabah,5,'0',STR_PAD_LEFT) }}">

                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Nasabah</label>
                                <input type="text" class="form-control" readonly value="{{ $nasabah->nama }}">
                            </div>
                        </div>

                        {{-- Rekening --}}
                        <div class='row'>
                             <div class="mb-3 col-md-6">
                                <div class='col-md-6'>
                            <label class="form-label">Tanggal Penarikan</label>
                            <input type="date" class="form-control" name='tgl_tarik' value={{old('tgl_tarik')}} required>
                              @error('tgl_tarik')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                             </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Rekening Tabungan</label>
                            <input type="text" class="form-control" readonly value="{{ $rekening->jenis_rekening }}">
                            <input type="hidden" name="id_rekening" value="{{ $rekening->id_rekening }}">
                        </div>
                        </div>

                        {{-- Saldo Sekarang --}}
                        <div class='row'>
                        
                         <div class="mb-3 col-md-4">
                            <label class="form-label">Saldo </label>
                            <input type="text" class="form-control" name='saldo' readonly value="{{ number_format($saldo,0) }}">
                        </div>
                         
                        </div>
                          <div class="row">
                       

                        {{-- Jumlah Penarikan --}}
                      
                        <div class="mb-3 col-md-4 ">
                            <div class='input-group input-group-static'>
                            <label class="">Penarikan Saldo</label>
                            <input type="text" class="form-control input-jumlah" name="tarik" value={{ old('tarik')??0}} >
                            </div>
                               @error('tarik')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        
                        </div>
                          </div>
                          <div class="row">
                        {{-- Metode Penarikan --}}
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Metode Penarikan</label>
                            <select class="form-control" name="metode" required>
                                <option value="">--Pilih Metode--</option>
                                <option value="tunai" @selected(old('metode')=='tunai')>Tunai</option>
                                <option value="non"  @selected(old('metode')=='non')>Non Tunai</option>
                            </select>
                            @error('metode')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- Keterangan --}}
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="2" placeholder="Opsional"></textarea>
                        </div>
                          </div>

                        {{-- Button --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Proses Penarikan</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>
    </main>
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

