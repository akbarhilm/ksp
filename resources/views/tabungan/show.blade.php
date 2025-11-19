<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="tabungan" menuParent="simpanan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="History Tabungan"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Transaksi Tabungan</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-2 py-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-4">
                                        <label>No Nasabah</label>
                                        <input type="text" readonly class="form-control" id="idnasabah"
                                            name="nama"
                                            value="{{ str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT) }}" />

                                    </div>
                                    @error('nama')
                                        <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Nama Nasabah</label>
                                        <input type="text" readonly class="form-control" name="nama"
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
                                     <label>Rekening</label>
                                            <input type="text" id="nama_rekening" class="form-control" readonly name="jenis" value="{{ $rekening[0]->jenis_rekening}}"/>
                                            <input type="hidden" id="id_rekening" name="id_rekening" value="{{ $rekening[0]->id_rekening}}"/>


                                    </div>
                                    @error('jenis_rekening')
                                        <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-4">
                                     <label>Bulan</label>
                                            <input type="month" id="tanggal" class="form-control"  name="tanggal" value="{{date('m')}}"/>


                                    </div>
                                    @error('jenis_rekening')
                                        <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <div class="input-group input-group-static mb-4">
                                        <button class="btn btn-info" onclick="lihat()">Lihat</button>
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
                    <div class="card my-4">

                        <div class="card-body px-4 pb-2 py-4">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Jenis</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Keterangan</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Debit</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Kredit</th>

                                        </tr>
                                    </thead>
                                    <tbody id="result">



                                    </tbody>
                                    <tfoot>
                                        {{-- <tr>
                                            <td colspan="8">
                                                <div class="d-flex justify-content-center">
                                                    {{ $nasabah->links() }}
                                                </div>
                                            </td>
                                        </tr> --}}
                                    </tfoot>
                                </table>
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
                    url: "{{ route('tabungan.lihat') }}",
                    type: "GET",
                    data: {
                        'idrekening': idrekening,
                        'tanggal': date
                    },
                    success: function(data) {
                        $('#result').html(''); // kosongkan dulu
                        if (data.length > 0) {
                            $.each(data, function(index, simpanan) {
                                $('#result').append(`
                            <tr>
                                <td>
                                     <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-start">
                                             ${simpanan.tanggal}
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-start">
                                            ${simpanan.jenis}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-start">
                                            ${simpanan.keterangan}
                                        </div>
                                    </div>
                                </td>
                                 <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-end">
                                            ${simpanan.v_debit}
                                        </div>
                                    </div>
                                </td>
                                 <td class="align-right text-center">
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-end">
                                            ${simpanan.v_kredit}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `);
                            });
                        } else {
                            $('#result').html('<tr><td colspan="2">Tidak ada hasil</td></tr>');
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
