<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register Anggota</title>

  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="wrapper">
  <div class="card">

    <!-- LEFT -->
    <div class="left">
      <h2>Register Anggota</h2>
      <img src="{{ asset('img/login.png') }}" alt="Register">
    </div>

    <!-- RIGHT -->
    <div class="right">
      <form action="{{ route('register-anggota') }}" method="POST">
                @csrf

        <label>Nama Lengkap</label>
        <input type="text" placeholder="Nama Lengkap">

        <label>Username</label>
        <input type="text" placeholder="Username">

        <label>No. Telp</label>
        <input type="text" placeholder="08xxxxxxxxxx">

        <!-- NIS & NISN -->
        <div class="row-input">
          <div>
            <label>NIS</label>
            <input type="text">
          </div>
          <div>
            <label>NISN</label>
            <input type="text">
          </div>
        </div>

        <label>Kelas</label>
        <input type="text" placeholder="Kelas">

        <label>Password</label>
        <div class="password">
          <input type="password" placeholder="Password">
          <i class="fa-solid fa-eye-slash"></i>
        </div>

        <div class="remember">
          <label>
            <input type="checkbox">
            Ingat Saya
          </label>
        </div>

        <button type="submit">Daftar</button>

        <p class="register">
          Sudah Memiliki Akun ?
          <a href="{{route('login.show')}}">Masuk</a>
        </p>

      </form>
    </div>

  </div>
</div>

</body>
</html>