<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="bukubesar" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Buku Besar"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container mt-4">

    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="mb-3">Buku Besar</h3>

            {{-- FILTER FORM --}}
            <form method="GET" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Akun</label>
                    <select name="id_akun" id="filter-akun" class="form-select">
                        <option value="">-- Semua Akun --</option>
                        @foreach ($akunList as $a)
                            <option value="{{ $a->id_akun }}">
                               
                                {{ $a->kode_akun .' / '.$a->nama_akun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control"
                           >
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                           >
                </div>

                <div class="col-md-2 d-grid">
                    <label class="form-label invisible">Filter</label>
                    <button class="btn btn-info">Filter</button>
                </div>

            </form>
        </div>
    </div>

    <hr>

    {{-- OUTPUT --}}
    {{-- @forelse ($bukuBesar as $akunId => $rows)
        @php
            $akun = $akunList->firstWhere('id_akun', $akunId);
        @endphp --}}

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info ">
                <h5 class="mb-0  text-white">
                    {{-- Akun: {{ $akun->nama_akun }} (ID: {{ $akunId }}) --}}
                </h5>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered" id="table-buku">
    <thead class="table-dark">
        <tr>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Debet</th>
            <th>Kredit</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
<tr class="fw-bold table-warning">
    <td colspan="2" class="text-center">TOTAL</td>
    <td id="totalDebet"  class="text-end">0</td>
    <td id="totalKredit" class="text-end">0</td>
    <td id="saldoAkhir"  class="text-end">0</td>
</tr>
</tfoot>
</table>


            </div>
        </div>

    {{-- @empty
        <div class="alert alert-warning mt-3">
            Tidak ada data untuk filter ini.
        </div>
    @endforelse --}}

</div>
    </main>
    {{-- <x-plugins></x-plugins> --}}
 @push('js')
        <script>
$(function(){

let table = $('#table-buku').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:"{{ route('bukubesar.index') }}",
        data:function(d){
            d.id_akun = $('#filter-akun').val();
            d.tanggal_awal = $('#tanggal_awal').val();
            d.tanggal_akhir = $('#tanggal_akhir').val();
        }
    },
    columns:[
        { data:'tanggal' },
        { data:'keterangan' },
        { data:'debet', className:'text-end',
            render: function (data) {
                return parseFloat(data).toLocaleString('id-ID');
            }
        },
        { data:'kredit', className:'text-end',
            render: function (data) {
                return parseFloat(data).toLocaleString('id-ID');
            }
        },
        { data:'saldo', className:'text-end',
            render: function (data) {
                return parseFloat(data).toLocaleString('id-ID');
            }
        }
    ]
});

function format(n){
    return new Intl.NumberFormat('id-ID').format(n);
}


table.on('xhr', function () {

    let json = table.ajax.json();

    $('#totalDebet').text(format(json.totalDebet));
    $('#totalKredit').text(format(json.totalKredit));
    $('#saldoAkhir').text(format(json.saldoAkhir));

});


// 🔁 reload saat filter berubah
$('#filter-akun,#tanggal_awal,#tanggal_akhir').on('change',function(){
    table.ajax.reload();
});

});
</script>
@endpush


</x-layout>
