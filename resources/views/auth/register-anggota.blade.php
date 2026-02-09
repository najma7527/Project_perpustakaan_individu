<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register Anggota</title>

  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
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
      <form action="{{ route('registerAnggota') }}" method="POST">
        @csrf

        <label>Nama Lengkap</label>
        <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required class="{{ $errors->has('name') ? 'input-error' : '' }}">
        @error('name')
            <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
        @enderror

        <label>Username</label>
        <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required class="{{ $errors->has('username') ? 'input-error' : '' }}">
        @error('username')
            <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
        @enderror

        <label>No. Telp</label>
        <input type="text" name="telephone" placeholder="08xxxxxxxxxx" value="{{ old('telephone') }}" class="{{ $errors->has('telephone') ? 'input-error' : '' }}">
        @error('telephone')
            <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
        @enderror

        <!-- NIS & NISN -->
        <div class="row-input">
          <div>
            <label>NIS</label>
            <input type="text" id="nis_field" placeholder="NIS" value="{{ old('nis') }}" class="{{ $errors->has('nis') || $errors->has('nis_nisn') ? 'input-error' : '' }}">
          </div>
          <div>
            <label>NISN</label>
            <input type="text" id="nisn_field" placeholder="NISN" value="{{ old('nisn') }}" class="{{ $errors->has('nisn') || $errors->has('nis_nisn') ? 'input-error' : '' }}">
          </div>
        </div>
        <!-- Hidden field untuk menyimpan gabungan NIS-NISN -->
        <input type="hidden" name="nis_nisn" id="nis_nisn_combined">
        @error('nis_nisn')
            <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
        @enderror

        <label>Kelas</label>
        <input type="text" name="kelas" placeholder="Kelas" value="{{ old('kelas') }}" class="{{ $errors->has('kelas') ? 'input-error' : '' }}">
        @error('kelas')
            <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
        @enderror

        <label>Password</label>
        <div class="password">
          <input type="password" name="password" placeholder="Password" required class="pwd-input {{ $errors->has('password') ? 'input-error' : '' }}">
          <i class="fa-solid fa-eye-slash pwd-toggle"></i>
        </div>
        @error('password')
            <small style="color: red; font-size: 12px; display: block; margin-top: 3px;">{{ $message }}</small>
        @enderror

        <div class="remember">
          <label>
            <input type="checkbox" name="remember">
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

<script>
    // Fungsi untuk toggle password visibility
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

    // Fungsi untuk menggabungkan NIS dan NISN
    function updateNisNisn() {
        const nisField = document.getElementById('nis_field').value;
        const nisnField = document.getElementById('nisn_field').value;
        const combined = (nisField || nisnField) ? nisField + '-' + nisnField : '';
        document.getElementById('nis_nisn_combined').value = combined;
    }

    // Update saat input berubah
    document.getElementById('nis_field').addEventListener('input', updateNisNisn);
    document.getElementById('nisn_field').addEventListener('input', updateNisNisn);

    // Update saat form di-submit
    document.querySelector('form').addEventListener('submit', updateNisNisn);
</script>

</body>
</html>