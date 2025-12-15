<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="jurnal" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Jurnal"></x-navbars.navs.auth>
        <!-- End Navbar -->
       <div class="container mt-4">


       
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h3 class="mb-3">Jurnal Transaksi</h3>
    <button class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#modalJurnal">
    <i class="bi bi-plus-circle"></i> Tambah Jurnal
</button>

<!-- MODAL TAMBAH JURNAL DOUBLE ENTRY -->
<div class="modal fade" id="modalJurnal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white">Tambah Jurnal (Double Entry)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('jurnal.storeDouble') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal_transaksi" class="form-control" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" required>
                        </div>

                        <hr>

                        <h6 class="text-primary fw-bold">Baris Debit</h6>
                        <div class="col-md-8">
    <label class="form-label">Akun Debit</label>
    <select name="akun_debet" id="akun_debet" class="form-select" required>
        <option value="">-- Pilih Akun --</option>
        @foreach ($akunList as $a)
            <option value="{{ $a->id_akun }}">{{ $a->kode_akun.' / '. $a->nama_akun }}</option>
        @endforeach
    </select>
</div>

                        <div class="col-md-4">
                            <label class="form-label">Jumlah Debit</label>
                            <input type="text" id="jumlah_debet" name="jumlah_debet" class="form-control " required min="0">
                        </div>

                        <h6 class="text-danger fw-bold mt-3">Baris Kredit</h6>
                        <div class="col-md-8">
                            <label class="form-label">Akun Kredit</label>
                            <select name="akun_kredit" id="akun_kredit" class="form-select" required>
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($akunList as $a)
                                    <option value="{{ $a->id_akun }}">{{$a->kode_akun.' / '.$a->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Kredit</label>
                            <input type="text" id="jumlah_kredit" name="jumlah_kredit" class="form-control " required min="0">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Simpan Jurnal
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
            {{-- FILTER FORM --}}
            <form id="filter-form" class="row g-3">

    

    <div class="col-md-3">
        <label class="form-label">Tanggal Awal</label>
        <input type="date" id="tanggal_awal" class="form-control">
    </div>

    <div class="col-md-3">
        <label class="form-label">Tanggal Akhir</label>
        <input type="date" id="tanggal_akhir" class="form-control">
    </div>

    
    

</form>

        </div>
    </div>

    {{-- TABEL JURNAL --}}
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
           <a href="#" id="btnDownloadRekap" class="btn btn-dark">
    Download Jurnal
</a>
        </div>
        <div class="card-body p-0 overflow-auto">

            <table class="table   mb-4" id="table-jurnal">
    <thead class="table-dark">
        <tr>
            <th>No Jurnal</th>
            <th>Tanggal</th>
            <th>Akun</th>
            <th>Keterangan</th>
            <th class="text-end">Debit</th>
            <th class="text-end">Kredit</th>
        </tr>
    </thead>
</table>


        </div>
    </div>

</div>
    </main>
    {{-- <x-plugins></x-plugins> --}}
@push('js')
<script>

$(document).ready(function() {
    $('#akun_debet').select2({
        placeholder: "-- Pilih Akun --",
        allowClear: true,
        width: '100%'
    });
     $('#akun_kredit').select2({
        placeholder: "-- Pilih Akun --",
        allowClear: true,
        width: '100%'
    });
      $('#filterAkun').select2({
        placeholder: "-- Pilih Akun --",
        allowClear: true,
        width: '100%'
    });
});

document.addEventListener('DOMContentLoaded', function () {

    const debit = document.getElementById("jumlah_debet");
    const kredit = document.getElementById("jumlah_kredit");

    // Jika debit berubah, kredit ikut
    debit.addEventListener("input", function() {
        kredit.value = this.value;
    });

    // Jika kredit berubah, debit ikut
    kredit.addEventListener("input", function() {
        debit.value = this.value;
          });

});
$(function(){


    let table = $('#table-jurnal').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        pageLength: 25,
        ajax: {
            url: "{{ route('jurnal.index') }}",
            data: function(d){
                d.tanggal_awal = $('#tanggal_awal').val();
                d.tanggal_akhir = $('#tanggal_akhir').val();
            }
        },
        

        columns: [
            { data: 'nojurnal',name:'nojurnal' },
            { data: 'tanggal_transaksi' },
            { data: 'akun',className:'text-wrap'  },
            { data: 'keterangan',className:'text-wrap' },
            { data: 'debit', className:'text-end' },
            { data: 'kredit', className:'text-end' }
        ]
    });
$('#tanggal_awal,#tanggal_akhir').on('change',function(){
    table.ajax.reload();
});
});

$('#btnDownloadRekap').on('click', function(e){
    e.preventDefault();

    let akun   = $('#filter-akun').val();
    let awal   = $('#tanggal_awal').val();
    let akhir  = $('#tanggal_akhir').val();

    let url = `{{ route('export.jurnal') }}?tanggal_awal=${awal}&tanggal_akhir=${akhir}`;
    window.open(url, '_blank');
});


</script>
@endpush
</x-layout>
