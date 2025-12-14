<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="jurnal" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Jurnal"></x-navbars.navs.auth>
<div class="container">

    <h4 class="mb-4">Edit Jurnal</h4>
    <form action="{{ route('jurnal.update', $jurnal[0]->no_jurnal) }}" id='rubah' method="POST">
        @csrf
        @method('PUT')

        {{-- Informasi Transaksi --}}
        <div class="card mb-4">
            <div class="card-header">Informasi Transaksi</div>
            <div class="card-body">
                <div class='row'>
                <div class="mb-3 col-md-3">
                    <label class="form-label">No Jurnal</label>
                    <input type="text" class="form-control" value="{{ $jurnal[0]->no_jurnal }}" readonly>
                </div>

                <div class="mb-3 col-md-3">
                    <label class="form-label">Tanggal Transaksi</label>
                    <input type="date" name="tanggal_transaksi" class="form-control"
                        value="{{ $jurnal[0]->tanggal_transaksi }}" required>
                </div>
                </div>
                <div class='row'>
                <div class="mb-3">
                    <div class='input-group input-group-static'>
                    <label class="">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control"
                        value="{{ $jurnal[0]->keterangan }}">
                    </div>
                </div>
                </div>
            </div>
        </div>

        {{-- Detail Akun --}}
        <div class="card mb-4">
            <div class="card-header">Detail Jurnal</div>
            <div class="card-body">

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40%">Akun</th>
                            <th style="width: 20%">Debit</th>
                            <th style="width: 20%">Kredit</th>
                        </tr>
                    </thead>
                    <tbody id="detail-body">
                        @foreach ($jurnal as $j)
                        <tr>
                            <td>
                                <select name="akun_id[]"  class="form-select" required>
                                    <option value="">-- Pilih Akun --</option>
                                    @foreach ($akun as $ak)
                                    <option value="{{ $ak->id_akun }}"
                                        {{ $ak->id_akun == $j->id_akun ? 'selected' : '' }}>
                                        {{ $ak->kode_akun }} - {{ $ak->nama_akun }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" id='jumlah_debet' name="v_debet[]" class="form-control"
                                       value="{{ $j->v_debet }}">
                                <input type='hidden' name='jenis[]' value='{{$j->jenis}}'>

                            </td>
                            <td>
                                <input type="number" id='jumlah_kredit' name="v_kredit[]" class="form-control"
                                       value="{{ $j->v_kredit }}">
                                <input type='hidden' name='id_jurnal[]' value='{{$j->id_jurnal}}'>

                            </td>
                           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
  <div class="row">
           <div class=' col-md-6 gap-2'>
            @if(!$angsuran)
            <a href="javascript:{}" onclick="rubah({{ $jurnal[0]->no_jurnal }})" class="btn btn-success " title="hapus">Simpan Perubahan
                </a>
                @endif
            {{-- <button type="submit" class="btn btn-success">Simpan Perubahan</button> --}}
             <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">Batal</a>
        </div>
        <div class='col-md-6 text-end'>
            @if(!$angsuran)
             <a href="javascript:{}" onclick="hapusjurnal({{ $jurnal[0]->no_jurnal }})" class="btn btn-sm btn-danger btn-link" title="hapus">Hapus
                </a>
                @endif
             {{-- <a href="{{ route('jurnal.hapus',$jurnal[0]->no_jurnal) }}" class="btn btn-danger">Hapus</a> --}}
        </div>
  </div>
                {{-- <button type="button" class="btn btn-primary" id="addRow">+ Tambah Baris</button> --}}

            </div>
        </div>

        {{-- Tombol --}}
      

    </form>
    <form id="formDelete" action="{{route('jurnal.destroy',$jurnal[0]->no_jurnal)}}" method="POST" >
                    @csrf
                    @method('DELETE')
                </form>
</div>
</main>

{{-- Script Tambah Baris --}}
<script>

function hapusjurnal(id) {
    if (confirm('Hapus Jurnal?')) {
        document.getElementById('formDelete').submit();
    }
}
   function rubah(id) {
    if (confirm('Simpan Perubahan?')) {
        document.getElementById('rubah').submit();
    }
}

    function attachRemoveEvents() {
        document.querySelectorAll('.removeRow').forEach(btn => {
            btn.onclick = function () {
                this.closest('tr').remove();
            };
        });
    }
    attachRemoveEvents();
    document.addEventListener('DOMContentLoaded', function () {

    const debit = document.getElementById("jumlah_debet");
    const kredit = document.getElementById("jumlah_kredit");

    // Jika debit berubah, kredit ikut
    

});
</script>

</x-layout>
