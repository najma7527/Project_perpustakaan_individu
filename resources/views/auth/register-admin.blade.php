<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

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
            <form method="POST" action="{{ route('register-admin') }}">
                @csrf

                <!-- Nama Lengkap -->
                <label>Nama Lengkap</label>
                <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required class="{{ $errors->has('name') ? 'input-error' : '' }}">
                @error('name')
                    <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
                @enderror

                <!-- Username -->
                <label>Username</label>
                <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required class="{{ $errors->has('username') ? 'input-error' : '' }}">
                @error('username')
                    <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
                @enderror

                <!-- No Telp -->
                <label>No. Telp</label>
                <input type="text" name="telephone" placeholder="08xxxxxxxxxx" value="{{ old('telephone') }}" required class="{{ $errors->has('telephone') ? 'input-error' : '' }}">
                @error('telephone')
                    <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
                @enderror

                <!-- Password -->
                <label>Password</label>
                <div class="password">
                    <input type="password" name="password" placeholder="Password" required class="pwd-input {{ $errors->has('password') ? 'input-error' : '' }}">
                    <i class="fa-solid fa-eye-slash pwd-toggle"></i>
                </div>
                @error('password')
                    <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
                @enderror

                <!-- Remember -->
                <div class="remember">
                    <label>
                        <input type="checkbox" name="remember">
                        Ingat Saya
                    </label>
                </div>

                <!-- Button -->
                <button type="submit">Daftar</button>

                <!-- Footer Text -->
                <p class="register">
                    Sudah Memiliki Akun ?
                    <a href="{{ route('login.show') }}">Masuk</a>
                </p>

            </form>
        </div>

    </div>
</div>

<script>
    document.querySelectorAll('.pwd-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            }
        });
    });
</script>

</body>
</html>