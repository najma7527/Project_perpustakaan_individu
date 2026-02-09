<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrasi Berhasil</title>
    
    <!-- Menyesuaikan path CSS dengan asset helper Laravel -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
    
    <!-- CSRF Token untuk keamanan -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body>
    <div class="register-anggota">
      <!-- Menyesuaikan path gambar dengan asset helper Laravel -->
      <img
        class="hourglass-dynamic"
        src="{{ asset('img/clock.jpg') }}"
        alt="Hourglass Timer"
      />
      <p class="registrasi-berhasil">
        <span class="text-wrapper">Registrasi berhasil!!<br /></span>
        <span class="div">akun anda sedang dalam peninjauan</span>
      </p>
      <div class="login">
        <!-- Menggunakan helper route() untuk navigasi yang lebih baik -->
        <a href="{{ route('login') }}" class="masuk">Back</a>
      </div>
    </div>
    
    <!-- Optional: Blade directives untuk konten dinamis -->
    @if (session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif
    
    <!-- Optional: Scripts jika diperlukan -->
    @stack('scripts')
  </body>
</html>