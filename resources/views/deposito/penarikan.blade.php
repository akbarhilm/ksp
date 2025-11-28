<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="deposito" menuParent="simpanan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Deposito"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card shadow-sm">
                <div class="card-header bg-success ">
                    <h5 class="mb-0 text-white">Form Penarikan Deposito</h5>
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
                        <div class="mb-3">
                            <label class="form-label">Rekening Deposito</label>
                            <input type="text" class="form-control" readonly value="{{ $rekening->jenis_rekening }}">
                            <input type="hidden" name="id_rekening" value="{{ $rekening->id_rekening }}">
                        </div>

                        {{-- Saldo Sekarang --}}
                        <div class='row'>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Saldo</label>
                            <input type="text" class="form-control" name="saldopokok" readonly value="{{ number_format($saldo,0) }}">
                        </div>
                        </div>

                        {{-- Jumlah Penarikan --}}
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Jumlah Penarikan</label>
                            <input type="text" class="form-control input-jumlah" name="jumlah" readonly value="{{ number_format($saldo,0)}}"  required>
                            @error('jumlah')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="2" placeholder="Opsional"></textarea>
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

