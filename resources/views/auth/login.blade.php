<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="wrapper">
    <div class="card">

        <!-- LEFT BLUE -->
        <div class="left">
            <h2>Login</h2>
            <img src="{{ asset('img/login.png') }}" alt="login">
        </div>

        <!-- RIGHT WHITE -->
        <div class="right">
            <form action="{{ route('login') }}" method="POST">
                @csrf

                {{-- USERNAME --}}
                <input 
                    type="text" 
                    name="username"
                    placeholder="Username"
                    value="{{ old('username') }}"
                    required
                    class="{{ $errors->has('username') ? 'input-error' : '' }}"
                >
                @error('username')
                    <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
                @enderror

                {{-- PASSWORD --}}
                <div class="password">
                    <input 
                        type="password" 
                        name="password"
                        placeholder="Password"
                        required
                        class="pwd-input {{ $errors->has('password') ? 'input-error' : '' }}"
                    >
                    <i class="fa-solid fa-eye-slash pwd-toggle"></i>
                </div>
                @error('password')
                    <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
                @enderror

                {{-- OPTIONS --}}
                <div class="row">
                   
                    {{-- INGAT SAYA --}}
                    <div class="remember">
                        <label>
                            <input type="checkbox" name="remember">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <button type="submit">Masuk</button>

                <p class="register">
                    Belum Memiliki Akun?
                    <a href="{{route('registerAnggota.show')}}">Daftar</a>
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
