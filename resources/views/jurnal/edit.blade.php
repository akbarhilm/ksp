<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="jurnal" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Jurnal"></x-navbars.navs.auth>
<div class="container">

    <h4 class="mb-4">Edit Jurnal</h4>

    <form action="{{ route('jurnal.update', $jurnal->id_jurnal) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Informasi Transaksi --}}
        <div class="card mb-4">
            <div class="card-header">Informasi Transaksi</div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">No Jurnal</label>
                    <input type="text" class="form-control" value="{{ $jurnal->no_jurnal }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Transaksi</label>
                    <input type="date" name="tanggal_transaksi" class="form-control"
                        value="{{ $jurnal->tanggal_transaksi }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control"
                        value="{{ $jurnal->keterangan }}">
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
                            <th style="width: 10%">#</th>
                        </tr>
                    </thead>
                    <tbody id="detail-body">
                        @foreach ($jurnal->detail as $detail)
                        <tr>
                            <td>
                                <select name="akun_id[]" class="form-control" required>
                                    <option value="">-- Pilih Akun --</option>
                                    @foreach ($akun as $ak)
                                    <option value="{{ $ak->id }}"
                                        {{ $ak->id == $detail->akun_id ? 'selected' : '' }}>
                                        {{ $ak->kode_akun }} - {{ $ak->nama_akun }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="debit[]" class="form-control"
                                       value="{{ $detail->debit }}">
                            </td>
                            <td>
                                <input type="number" name="kredit[]" class="form-control"
                                       value="{{ $detail->kredit }}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="button" class="btn btn-primary" id="addRow">+ Tambah Baris</button>

            </div>
        </div>

        {{-- Tombol --}}
        <div class="text-end">
            <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>

    </form>
</div>
</main>
</x-layout>

{{-- Script Tambah Baris --}}
<script>
    document.getElementById('addRow').addEventListener('click', function () {
        let row = `
        <tr>
            <td>
                <select name="akun_id[]" class="form-control" required>
                    <option value="">-- Pilih Akun --</option>
                    @foreach ($akun as $ak)
                    <option value="{{ $ak->id }}">{{ $ak->kode_akun }} - {{ $ak->nama_akun }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="debit[]" class="form-control"></td>
            <td><input type="number" name="kredit[]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
        </tr>`;

        document.getElementById('detail-body').insertAdjacentHTML('beforeend', row);
        attachRemoveEvents();
    });

    function attachRemoveEvents() {
        document.querySelectorAll('.removeRow').forEach(btn => {
            btn.onclick = function () {
                this.closest('tr').remove();
            };
        });
    }
    attachRemoveEvents();
</script>

@endsection
