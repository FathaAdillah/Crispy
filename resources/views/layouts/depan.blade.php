<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title') - Kuisioner</title>
    <!-- CSS files -->
    <link href="./css/tabler.min.css?1692870487" rel="stylesheet" />
    <link href="./css/tabler-flags.min.css?1692870487" rel="stylesheet" />
    <link href="./css/tabler-payments.min.css?1692870487" rel="stylesheet" />
    <link href="./css/tabler-vendors.min.css?1692870487" rel="stylesheet" />
    <link href="./css/demo.min.css?1692870487" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    @stack('style')

    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body>
    <script src="./js/demo-theme.min.js?1692870487"></script>
    @include('layouts.alert-success')
    @include('layouts.alert-fail')
    <div class="page">
        <!-- Navbar Atas-->
        @include('components.header-up-depan')

        <!-- Navbar Bawah-->
        @include('components.header-menu-depan')

        <div class="page-wrapper">

            <!-- Page header -->

            <!-- Content -->
            @yield('main')

            <!--Footer-->
            @include('components.footer')

        </div>
    </div>
    <!-- Libs JS -->
    <script src="./libs/apexcharts/dist/apexcharts.min.js?1692870487" defer></script>
    <script src="./libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487" defer></script>
    <script src="./libs/jsvectormap/dist/maps/world.js?1692870487" defer></script>
    <script src="./libs/jsvectormap/dist/maps/world-merc.js?1692870487" defer></script>
    <!-- Tabler Core -->
    <script src="./js/tabler.min.js?1692870487" defer></script>
    <script src="./js/demo.min.js?1692870487" defer></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                var successModal = new bootstrap.Modal(document.getElementById('modal-success'));
                successModal.show();
                setTimeout(() => {
                    successModal.hide();
                }, 5000);
            @endif

            @if(session('error'))
                var errorModal = new bootstrap.Modal(document.getElementById('modal-danger'));
                errorModal.show();
                setTimeout(() => {
                    errorModal.hide();
                }, 5000);
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>
