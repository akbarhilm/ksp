<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="simpanan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Simpanan"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Tambah Simpanan</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">



                                <form method="POST" action="{{ route('simpanan.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Anggota</label>
                                        <select name="id_anggota" class="w-full border p-2 rounded">
                                            @foreach ($anggota as $a)
                                                <option value="{{ $a->id_anggota }}">{{ $a->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Jenis</label>
                                        <select name="jenis" class="w-full border p-2 rounded">
                                            <option value="pokok">Pokok</option>
                                            <option value="wajib">Wajib</option>
                                            <option value="sukarela">Sukarela</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Jumlah</label>
                                        <input type="number" name="jumlah" class="w-full border p-2 rounded" required>
                                    </div>

                                    <button type="submit"
                                        class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>
