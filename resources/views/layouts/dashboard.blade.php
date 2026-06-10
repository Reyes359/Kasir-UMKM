<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kasir & Manajemen Stok UMKM')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.dashboard.styles')
</head>
<body class="h-full">
    @yield('content')

    <div id="toastContainer"></div>
    <div id="modalContainer"></div>

    @include('partials.dashboard.scripts')
</body>
</html>
