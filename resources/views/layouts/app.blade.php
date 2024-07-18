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
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <title>@yield('title') - Kuisioner</title>
    <!-- CSS files -->
    <link href="./css/tabler.min.css?1692870487" rel="stylesheet" />
    <link href="./css/tabler-flags.min.css?1692870487" rel="stylesheet" />
    <link href="./css/tabler-payments.min.css?1692870487" rel="stylesheet" />
    <link href="./css/tabler-vendors.min.css?1692870487" rel="stylesheet" />
    <link href="./css/demo.min.css?1692870487" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <!-- Custom CSS -->
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
    {{-- @include('layouts.alert-success')
    @include('layouts.alert-fail') --}}
    <div class="page">
        <!-- Navbar Atas-->
        @include('components.header-up')

        <!-- Navbar Bawah-->
        @include('components.header-menu')

        <div class="page-wrapper">
            <!-- Page header -->

            <!-- Content -->
            @yield('main')

            <!-- Footer -->
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

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

    <!-- Custom Scripts -->
    @stack('scripts')

</body>

</html>
