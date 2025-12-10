<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="pencairan" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pencairan Pengajuan Pinjaman"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

                        <div class="card-header p-0 mt-n4 mx-3 z-index-2 position-relative">
                            <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white ps-3 mb-0">Pencairan Pengajuan Pinjaman</h6>
                            </div>
                        </div>

                        <div class="card-body px-4 py-4">


                            {{-- DATA NASABAH --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No Nasabah</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ str_pad($pengajuan->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Nasabah</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ $pengajuan->rekening[0]->nasabah[0]->nama }}">
                                </div>
                            </div>

                            {{-- REKENING & BUNGA --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Rekening</label>
                                    <input type="text" readonly class="form-control"
                                        value="{{ $pengajuan->rekening[0]->no_rekening }} / {{ $pengajuan->rekening[0]->jenis_rekening }}">

                                    <input type="hidden" name="id_rekening"
                                        value="{{ $pengajuan->rekening[0]->id_rekening }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bunga per Bulan (%)</label>
                                    <input type="number" id="bunga" name="bunga" readonly
                                        class="form-control input-bunga" value="{{ $pengajuan->bunga }}">
                                    @error('bunga')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- JUMLAH PINJAMAN & TENOR --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jumlah Pinjaman</label>
                                    <input type="text" id="jumlah" name="jumlah_pengajuan" readonly
                                        class="form-control format-angka  input-jumlah"
                                        value="{{ number_format($pengajuan->jumlah_pengajuan) }}">
                                    @error('jumlah_pengajuan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tenor (bulan)</label>
                                    <input type="number" readonly id="tenor" name="tenor"
                                        value="{{ $pengajuan->tenor }}" class="form-control input-tenor">
                                    @error('tenor')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- CICILAN PER BULAN --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Cicilan Per Bulan</label>
                                    <input type="text" readonly name="cicilan" id="cicilan"
                                        value="{{ $pengajuan->cicilan }}" class="form-control bg-light fw-bold">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Kode Resort</label>
                                    <input type="text" readonly name="kode_resort"
                                        value="{{ $pengajuan->kode_resort }}" class="form-control">
                                    @error('kode_resort')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                </div>
                            </div>
                            @if($pengajuan->status == 'approv')
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Metode Pencairan</label>
                                    <select class="form-control" name="metode">
                                        <option value=''>===Pilih Metode Pencairan===</option>
                                        <option value="tunai">Tunai</option>
                                        <option value="non">Non Tunai</option>
                                    </select>
                                   <small id="error-metode" class="text-danger"></small>


                                </div>

                            </div>
                            @endif

                            <hr>
                            <h6 class="mb-3">Potongan</h6>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Simpanan Pokok</label>
                                    <input type="text" readonly name="simpanan_pokok"
                                        value="{{ number_format($pengajuan->simpanan_pokok, 0) }}"
                                        class="form-control format-angka  input-jumlah">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Provisi</label>
                                    <input type="text" readonly name="admin"
                                        value="{{ number_format($pengajuan->admin, 0) }}"
                                        class="form-control format-angka  input-jumlah">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Asuransi</label>
                                    <input type="text" readonly name="asuransi"
                                        value="{{ number_format($pengajuan->asuransi, 0) }}"
                                        class="form-control format-angka  input-jumlah">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">survey</label>
                                    <input type="text" readonly name="survey"
                                        value="{{ number_format($pengajuan->survey, 0) }}"
                                        class="form-control format-angka  input-jumlah">
                                </div>
                            </div>
                                @if ($pengajuan->jenis == 'topup')
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Sisa Pinjaman Sebelumnya</label>
                                        <input type="text" readonly name="asuransi"
                                            value="{{ number_format($pinjaman->sisa_pokok, 0) }}"
                                            class="form-control format-angka  input-jumlah">
                                    </div>
                                </div>
                                @endif


                            <hr>

                            {{-- INPUT JAMINAN DINAMIS --}}
                            <h6 class="mb-3">Jaminan</h6>

                            <div id="jaminan-container">
                                @foreach ($jaminan as $j)
                                    <div class="row jaminan-item mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label">Jenis Jaminan</label>
                                            <input type="text" name="jenis_jaminan[]" class="form-control"
                                                placeholder="Contoh: ATM" value='{{$j->jenis_jaminan}}'>
                                        </div>

                                        <div class="col-md-5">
                                            <label class="form-label">Keterangan</label>
                                            <input type="text" name="keterangan[]" class="form-control"
                                                placeholder="Contoh: No kartu 1234..." value='{{$j->keterangan}}'>
                                        </div>


                                    </div>
                                @endforeach
                            </div>


                            {{-- TOMBOL --}}
                            <div class="d-flex gap-2">
                                @if($pengajuan->status == 'approv')
                                <button id="btnCair" class="btn btn-info" data-id="{{ $pengajuan->id_pengajuan }}"
                                    title="Cairkan">
                                    Cairkan<i class="material-icons">print</i>
                                </button>
                                @endif
                                <a href="{{ url()->previous() }}" class="btn btn-dark">Kembali</a>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>




    </main>
    @push('js')
        <script>
            $('#btnCair').click(function() {
                let metode = $('select[name="metode"]').val();
                let id = $(this).data('id');

                $.get('/pengajuan/cair/' + id + '?metode=' + metode)
                    .then(function(res) {

                        if (res.success) {
                            window.open(res.pdf_url, '_blank');
                            location.reload();
                        }

                    })
                    .fail(function(xhr) {
                        let errors = xhr.responseJSON.errors;

    if(errors.metode){
        $('#error-metode').html(errors.metode[0]);
    }
                    });
            });
            $(document).ready(function() {

                let jumlah = toNumber($("#jumlah").val().replace(/,/g, ''));
                let bunga = parseFloat($("#bunga").val());
                let tenor = parseInt($("#tenor").val());
                if (jumlah > 0 && !isNaN(bunga) && tenor > 0) {

                    let totalBunga = (jumlah * bunga * tenor / 100);
                    let total = jumlah + totalBunga;
                    let cicilan = total / tenor;

                    $("#cicilan").val(formatAngka(cicilan.toFixed(0)));
                } else {
                    $("#cicilan").val("");
                }
            });

            // ================= TRIGGER SAAT BUNGA / TENOR BERUBAH ===========
        </script>
    @endpush
</x-layout>
