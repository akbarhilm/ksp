<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="deposito" menuParent="simpanan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Deposito"></x-navbars.navs.auth>
        <!-- End Navbar -->
       <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <!-- CARD DATA NASABAH -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white py-3 px-4">
                    <h6 class="mb-0 text-white">Transaksi Tabungan</h6>
                </div>

                <div class="card-body px-4 py-4">

                    <!-- ROW 1 -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No Anggota</label>
                            <input type="text" class="form-control" readonly
                                   value="{{ str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Anggota</label>
                            <input type="text" class="form-control" readonly value="{{ $nasabah->nama }}">
                        </div>
                    </div>

                    <!-- ROW 2 -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rekening</label>
                            <input type="text" class="form-control" readonly
                                   value="{{ $rekening[0]->jenis_rekening }}">
                            <input type="hidden" id="id_rekening" name="id_rekening"
                                   value="{{ $rekening[0]->id_rekening }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bulan</label>
                            <input type="month" class="form-control" id="tanggal" name="tanggal"
                                   value="{{ date('Y-m') }}">
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                            <button type="button" class="btn btn-info" onclick="lihat()">Lihat</button>
                       
                            <a class="btn btn-dark" href="{{ url()->previous() }}">Kembali</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- CARD TABEL -->
            <div class="card shadow-sm mb-4">
                <div class="card-body px-4 py-4">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-items-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Debit</th>
                                    <th class="text-center">Kredit</th>
                                </tr>
                            </thead>

                            <tbody id="result">
                                <!-- hasil AJAX masuk di sini -->
                            </tbody>

                        </table>
                    </div>

                    <!-- SALDO AKHIR -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-end">
                            <div class="text-end">
                                <h6 class="mb-1">Saldo Akhir:</h6>
                                <h4 id="saldo_akhir" class="fw-bold">Rp 0</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

    </main>
    @push('js')
        <script>
            function lihat() {
                var idrekening = $("#id_rekening").val()
                var date = $("#tanggal").val()
                $.ajax({
                    url: "{{ route('deposito.lihat') }}",
                    type: "GET",
                    data: {
                        'idrekening': idrekening,
                        'tanggal':date
                    },
                    success: function(data) {
            $('#result').html(''); // kosongkan dulu

            let totalDebit = 0;
            let totalKredit = 0;

            if (data.length > 0) {

                $.each(data, function(index, simpanan) {

                    // hitung total
                    totalDebit += parseInt(simpanan.v_debit);
                    totalKredit += parseInt(simpanan.v_kredit);

                    $('#result').append(`
                        <tr>
                            <td>${simpanan.tanggal}</td>
                            <td>${simpanan.jenis}</td>
                            <td>${simpanan.keterangan}</td>
                            <td class="text-end">${Number(simpanan.v_debit).toLocaleString()}</td>
                            <td class="text-end">${Number(simpanan.v_kredit).toLocaleString()}</td>
                        </tr>
                    `);
                });

                // ======================
                //   HITUNG SALDO AKHIR
                // ======================
                let saldoAkhir =  totalKredit - totalDebit;

                // tampilkan saldo
                $("#saldo_akhir").text("Rp " + saldoAkhir.toLocaleString());

            } else {
                $('#result').html('<tr><td colspan="5" class="text-center">Tidak ada hasil</td></tr>');
                $("#saldo_akhir").text("Rp 0");
            }
        }
    });
            }

            function setnama() {
                var select = document.getElementById("jenis_rekening");
                var selectedOption = select.options[select.selectedIndex];
                var namaRekening = selectedOption.text;
                document.getElementById("nama_rekening").value = namaRekening;

            }

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
