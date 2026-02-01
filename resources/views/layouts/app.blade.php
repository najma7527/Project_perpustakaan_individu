<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    {{-- CSS GLOBAL --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- CSS TAMBAHAN PER HALAMAN --}}
    @stack('styles')

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app-container">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- TOPBAR --}}
    <header class="topbar">
        <i class="fa fa-bars"></i>
        <div class="user">
            <span>Seulgi</span>
            <small>Admin</small>
            <img src="{{ asset('img/user.png') }}">
        </div>
    </header>

    {{-- CONTENT --}}
    <main class="content">
        @yield('content')
    </main>

</div>

</body>
</html>
