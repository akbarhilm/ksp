<!--
=========================================================
* Material Dashboard 2 - v3.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
@props(['bodyClass'])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('assets') }}/img/favicon.png">
    <title>
        KSP SMS
    </title>
    <!--     Fonts and icons     -->
    {{-- <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" /> --}}
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets') }}/css/nucleo-icons.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js" ></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets') }}/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
  
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
/* Sidebar mobile */
@media (max-width: 1199px) {

    #sidebar {
        transform: translateX(-110%);
        transition: all 0.3s ease;
        position: fixed;
        z-index: 1050;
        top: 0;
        left: 0;
        height: 100vh;
        border-radius: 0 1rem 1rem 0;
    }

    #sidebar.active {
        transform: translateX(0);
    }

    body.sidebar-open {
        overflow: hidden;
    }
    #sidebar .collapse {
    transition: all .25s ease;
}

}
</style>


</head>
<body class="{{ $bodyClass }}">

{{ $slot }}

<script src="{{ asset('assets') }}/js/core/popper.min.js"></script>
<script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/smooth-scrollbar.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('js')
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
function formatAngka(angka) {
    return angka
        .toString()
        .replace(/[^0-9]/g, "")
        .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$(document).on("input", ".input-jumlah", function () {
   let input = this;

    // posisi kursor lama (jumlah digit sebelum kursor)
    let start = input.selectionStart;
    let angkaSebelum = input.value.slice(0, start).replace(/\D/g, "").length;

    // format ulang
    let formatted = formatAngka(input.value);
    input.value = formatted;

    // cari posisi kursor baru berdasarkan digit
    let pos = 0, count = 0;
    while (count < angkaSebelum && pos < formatted.length) {
        if (/\d/.test(formatted[pos])) count++;
        pos++;
    }

    input.setSelectionRange(pos, pos);
    hitungCicilan();
});

$(document).on("keypress", ".input-jumlah", function (e) {
    const charCode = (e.which) ? e.which : e.keyCode;
    if (charCode < 48 || charCode > 57) {
        e.preventDefault();
    }
});

function toNumber(str) {
    if (!str) return 0;
    return parseInt(str.replace(/\./g, "")) || 0;
}

document.addEventListener("DOMContentLoaded", function(){
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');

    if(toggle) {
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            document.body.classList.toggle('sidebar-open');
        });
    }

    // klik di luar sidebar = tutup
    document.addEventListener('click', function(e) {
        if (sidebar.classList.contains('active') && 
            !sidebar.contains(e.target) && 
            !toggle.contains(e.target)) {
            sidebar.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        }
    });
});


</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('assets') }}/js/material-dashboard.min.js?v=3.0.0"></script>
</body>
</html>
