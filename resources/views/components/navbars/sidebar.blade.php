@props(['activePage', 'menuParent'])

<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3  bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href=" {{ route('dashboard') }} ">
            <img src="{{ asset('assets') }}/img/logos/koperasi.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bold text-white">KSP Sinar Murni Sejahtera</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            {{-- <li class="nav-item mt-3">
                 <a class="nav-link text-white {{ $activePage == 'dashboard' ? ' active bg-gradient-info' : '' }} "
                    href="{{ route('dashboard') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">home</i>
                    </div>
                    <span class="nav-link-text ms-1">
                        Home</span>
                </a>
               
            </li> --}}
            <li class="nav-item mt-3">
                <a class="nav-link ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8" data-bs-toggle="collapse" href="#collapseAdmin" role="button" aria-expanded="false" aria-controls="collapseAdmin">
                Admin</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'user-profile' ? 'active bg-gradient-info' : '' }} "
                    href="{{ route('user-profile') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1.2rem;" class="fas fa-user-circle ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Nasabah</span>
                </a>
            </li> --}}
            <div class="collapse {{$menuParent == 'admin' ? 'show':''}}" id="collapseAdmin">
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'nasabah' ? ' active bg-gradient-info' : '' }} "
                    href="{{ route('nasabah.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    <span class="nav-link-text ms-1">Data Nasabah</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'karyawan' ? ' active bg-gradient-info' : '' }} "
                    href="{{ route('users.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">badge</i>
                    </div>
                    <span class="nav-link-text ms-1">Data Karyawan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'rekening' ? ' active bg-gradient-info' : '' }} "
                    href="{{ route('rekening.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">wallet</i>
                    </div>
                    <span class="nav-link-text ms-1">Data Rekening</span>
                </a>
            </li>
            </div>
            <li class="nav-item mt-3">
                <a class="nav-link ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8" data-bs-toggle="collapse" href="#collapseSave" role="button" aria-controls="collapseSave">
                Simpanan</a>
            </li>
            <div class="collapse {{$menuParent == 'simpanan' ? 'show':''}}" id="collapseSave">
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'tabungan' ? ' active bg-gradient-info' : '' }} "
                    href="{{ route('tabungan.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">account_balance</i>
                    </div>
                    <span class="nav-link-text ms-1">Tabungan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'deposito' ? ' active bg-gradient-info' : '' }} "
                    href="{{ route('deposito.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">table_view</i>
                    </div>
                    <span class="nav-link-text ms-1">Deposito</span>
                </a>
            </li>
            </div>
             <li class="nav-item mt-3">
                <a class="nav-link ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8" data-bs-toggle="collapse" href="#collapseLoan" role="button" aria-controls="collapseLoan">
                Pinjaman</a>
            </li>
            <div class="collapse {{$menuParent == 'loan' ? 'show':''}}" id="collapseLoan">
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'pengajuan' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('pengajuan.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt_long</i>
                    </div>
                    <span class="nav-link-text ms-1">Pengajuan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'approval' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('pengajuan.approval') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">assignment_turned_in</i>
                    </div>
                    <span class="nav-link-text ms-1">Approval Pengajuan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'cair' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('pengajuan.pencairan') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">paid</i>
                    </div>
                    <span class="nav-link-text ms-1">Pencairan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'angsuran' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('pinjaman.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">payments</i>
                    </div>
                    <span class="nav-link-text ms-1">Pembayaran Angsuran</span>
                </a>
            </li>
            </div>

            <li class="nav-item mt-3">
                <a class="nav-link ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8" data-bs-toggle="collapse" href="#collapseLap" role="button" aria-controls="collapseLap">
                Laporan</a>
            </li>
            <div class="collapse {{$menuParent == 'laporan' ? 'show':''}}" id="collapseLap">
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'jurnal' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('jurnal.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">auto_stories</i>
                    </div>
                    <span class="nav-link-text ms-1">Jurnal</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'bukubesar' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('bukubesar.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">book</i>
                    </div>
                    <span class="nav-link-text ms-1">Buku Besar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'neraca' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('neraca.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">balance</i>
                    </div>
                    <span class="nav-link-text ms-1">Neraca</span>
                </a>
            </li>
             <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'labarugi' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('labarugi.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">request_page</i>
                    </div>
                    <span class="nav-link-text ms-1">Laba/Rugi</span>
                </a>
            </li>
            </div>
            
            {{-- <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'rtl' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('rtl') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">format_textdirection_r_to_l</i>
                    </div>
                    <span class="nav-link-text ms-1">RTL</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'notifications' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('notifications') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">notifications</i>
                    </div>
                    <span class="nav-link-text ms-1">Notifications</span>
                </a>
            </li> --}}
            {{-- <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'profile' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('profile') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li> --}}
            {{-- <li class="nav-item">
                <a class="nav-link text-white " href="{{ route('static-sign-in') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">login</i>
                    </div>
                    <span class="nav-link-text ms-1">Sign In</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="{{ route('static-sign-up') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">assignment</i>
                    </div>
                    <span class="nav-link-text ms-1">Sign Up</span>
                </a>
            </li> --}}
        </ul>
    </div>
    {{-- <div class="sidenav-footer position-absolute w-100 bottom-0 ">
        <div class="mx-3">
            <a class="btn bg-gradient-info w-100" href="https://www.creative-tim.com/product/material-dashboard-laravel" target="_blank">Free Download</a>
        </div>
        <div class="mx-3">
            <a class="btn bg-gradient-info w-100" href="../../documentation/getting-started/installation.html" target="_blank">View documentation</a>
        </div>
        <div class="mx-3">
            <a class="btn bg-gradient-info w-100"
                href="https://www.creative-tim.com/product/material-dashboard-pro-laravel" target="_blank" type="button">Upgrade
                to pro</a>
        </div>
    </div> --}}
</aside>
