<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
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

                {{-- EMAIL --}}
                <input 
                    type="email" 
                    name="email"
                    placeholder="Email"
                    value="{{ old('email', 'sikiyara@gmail.com') }}"
                    required
                >

                {{-- PASSWORD --}}
                <div class="password">
                    <input 
                        type="password" 
                        name="password"
                        placeholder="Password"
                        required
                    >
                    <i class="fa-solid fa-eye-slash"></i>
                </div>

                {{-- OPTIONS --}}
                <div class="row">
                   
<!-- INGAT & LUPA -->
                <div class="remember">
                    <label>
                        <input type="checkbox">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit">Masuk</button>

                <p class="register">
                    Belum Memiliki Akun?
                    <a href="{{route('register.show')}}">Daftar</a>
                </p>

            </form>
        </div>

                {{-- ERROR MESSAGE --}}
                @if ($errors->any())
                    <div style="margin-top:10px; color:red; font-size:13px;">
                        {{ $errors->first() }}
                    </div>
                @endif

            </form>
        </div>

    </div>
</div>

</body>
</html>
