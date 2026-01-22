<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <!-- Font Awesome (icon mata) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="wrapper">
    <div class="card">

        <!-- ================= LEFT ================= -->
        <div class="left">
            <h2>Register Admin</h2>

            <!-- Ganti sesuai asset kamu -->
            <img src="{{ asset('img/login.png') }}" alt="Register Admin">
        </div>

        <!-- ================= RIGHT ================= -->
        <div class="right">
            <form method="POST" action="#">
                @csrf

                <!-- Nama Lengkap -->
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" placeholder="Nama Lengkap">

                <!-- Username -->
                <label>Username</label>
                <input type="text" name="username" placeholder="Username">

                <!-- No Telp -->
                <label>No. Telp</label>
                <input type="text" name="no_telp" placeholder="08xxxxxxxxxx">

                <!-- Password -->
                <label>Password</label>
                <div class="password">
                    <input type="password" name="password" placeholder="Password">
                    <i class="fa-solid fa-eye-slash"></i>
                </div>

                <!-- Remember -->
                <div class="remember">
                    <label>
                        <input type="checkbox">
                        Ingat Saya
                    </label>
                </div>

                <!-- Button -->
                <button type="submit">Masuk</button>

                <!-- Footer Text -->
                <p class="register">
                    Belum Memiliki Akun ?
                    <a href="{{ url('/login') }}">Login</a>
                </p>

            </form>
        </div>

    </div>
</div>

</body>
</html>