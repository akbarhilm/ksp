<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="pengajuan" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pengajuan Pinjaman"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">

                <div class="card-header p-0 mt-n4 mx-3 z-index-2 position-relative">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white ps-3 mb-0">Pengajuan Pinjaman</h6>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    <form method="POST" action="{{ route('pengajuan.store') }}">
                        @csrf

                        {{-- DATA NASABAH --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No Anggota</label>
                                <input type="text" readonly class="form-control"
                                    value="{{ str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Anggota</label>
                                <input type="text" readonly class="form-control" value="{{ $nasabah->nama }}">
                            </div>
                        </div>

                        {{-- REKENING & BUNGA --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rekening</label>
                                <input type="text" readonly class="form-control"
                                    value="{{ $rekening[0]->no_rekening }} / {{ $rekening[0]->jenis_rekening }}">

                                <input type="hidden" name="id_rekening" value="{{ $rekening[0]->id_rekening }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class='input-group input-group-static'>

                                <label class="">Bunga per Bulan (%)</label>
                                <input type="number" id="bunga" name="bunga" class="form-control input-bunga"
                                    value="{{ old('bunga') }}">
                                @error('bunga') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            </div>
                        </div>

                        {{-- JUMLAH PINJAMAN & TENOR --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class='input-group input-group-static'>

                                <label class="">Jumlah Pinjaman</label>
                                <input type="text" 
         id="jumlah" name="jumlah_pengajuan" class="form-control format-angka  input-jumlah"
                                    value="{{ old('jumlah_pengajuan') }}">
                                @error('jumlah_pengajuan') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class='input-group input-group-static'>

                                <label class="">Tenor (bulan)</label>
                                <input type="number" id="tenor" name="tenor" value="{{ old('tenor') }}" class="form-control input-tenor">
                                @error('tenor') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            </div>
                        </div>

                        {{-- CICILAN PER BULAN --}}
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class='input-group input-group-static'>

                                <label class="">Cicilan Per Bulan</label>
                                <input type="text" readonly name="cicilan" id="cicilan" value="{{ old('cicilan') }}" class="form-control bg-light fw-bold">
                            </div>
                            </div>
                            
                        </div>

                        <hr>
                          <h6 class="mb-3">Potongan</h6>
                          <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class='input-group input-group-static'>

                                <label class="">Simpanan Pokok</label>
                                <input type="text"  name="simpanan_pokok"  value="{{ old('simpanan_pokok') }}" class="form-control format-angka  input-jumlah">
                             @error('simpanan_pokok') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            </div>
                             <div class="col-md-6 mb-4">
                                <div class='input-group input-group-static'>

                                <label class="">Provisi</label>
                                <input type="text"  name="admin"  value="{{ old('admin') }}" class="form-control format-angka  input-jumlah">
                             @error('admin') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                             </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class='input-group input-group-static'>

                                <label class="">Asuransi</label>
                                <input type="text"  name="asuransi" value="{{ old('asuransi') }}" class="form-control format-angka  input-jumlah">
                            @error('asuransi') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class='input-group input-group-static'>

                                <label class="">Survey</label>
                                <input type="text"  name="survey" value="{{ old('survey') }}" class="form-control format-angka  input-jumlah">
                             @error('survey') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            </div>
                            
                        </div>
                        <div class='row'>
                            <div class='col-md-6 mb-4'>
                            <div class='input-group input-group-static'>
                                <label>Materai</label>
                                <input type="text" name="materai" value='{{ old('materai') }}' class='form-control input-jumlah'>
                             @error('materai') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        </div>

                        <hr>

                        {{-- INPUT JAMINAN DINAMIS --}}
                        <h6 class="mb-3">Jaminan</h6>

                        <div id="jaminan-container">
                            @if($errors->has('jenis_jaminan.*')||$errors->has('keterangan.*'))
    <div class="text-danger">
        Semua jenis jaminan dan keterangan wajib diisi
    </div>
@endif

                            <div class="row jaminan-item mb-3">
                                <div class="col-md-5">
                                    <label class="form-label">Jenis Jaminan</label>
                                    <input type="text" name="jenis_jaminan[]" class="form-control" placeholder="Contoh: ATM">
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label">Keterangan</label>
                                    <input type="text" name="keterangan[]" class="form-control" placeholder="Contoh: No kartu 1234...">
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger w-100 remove-jaminan">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="addJaminan" class="btn btn-secondary mb-4">+ Tambah Jaminan</button>

                        {{-- TOMBOL --}}
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-info" type="submit">Simpan</button>
                            <a href="{{ url()->previous() }}" class="btn btn-dark">Kembali</a>

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

      function hitungCicilan() {

    let jumlah = toNumber($("#jumlah").val());
    let bunga  = parseFloat($("#bunga").val());
    let tenor  = parseInt($("#tenor").val());

    if (jumlah > 0 && !isNaN(bunga) && tenor > 0) {

        let totalBunga = (jumlah * bunga * tenor / 100);
        let total = jumlah + totalBunga;
        let cicilan = total / tenor;

        $("#cicilan").val( formatAngka(cicilan.toFixed(0)) );
    } else {
        $("#cicilan").val("");
    }
}

// ================= TRIGGER SAAT BUNGA / TENOR BERUBAH ===========
$("#bunga, #tenor").on("input", function () {
    hitungCicilan();
});

    // JAMINAN DINAMIS
    document.getElementById('addJaminan').addEventListener('click', function() {
        let container = document.getElementById('jaminan-container');

        let html = `
            <div class="row jaminan-item mb-3">
                <div class="col-md-5">
                    <label class="form-label">Jenis Jaminan</label>
                    <input type="text" name="jenis_jaminan[]" class="form-control" placeholder="Contoh: ATM">
                </div>

                <div class="col-md-5">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan[]" class="form-control" placeholder="Contoh: No kartu 1234...">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100 remove-jaminan">Hapus</button>
                </div>
            </div>
        `;

        container.insertAdjacentHTML("beforeend", html);
    });

    // Hapus baris jaminan
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-jaminan')) {
            e.target.closest('.jaminan-item').remove();
        }
    });
    
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

