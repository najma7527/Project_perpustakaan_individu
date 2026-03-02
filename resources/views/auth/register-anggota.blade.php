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
   <form action="{{ route('registerAnggota') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- FOTO -->
    <div class="photo-upload">
  <input type="file" id="photo" name="photo_profile" accept="image/*" hidden>
  
  <label for="photo" class="photo-circle" id="photoCircle">
      <i class="fa-solid fa-camera" id="cameraIcon"></i>
      <img id="previewImage" alt="Preview">
  </label>
</div>


    <label>Nama Lengkap</label>
    <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}">

    <label>Username</label>
    <input type="text" name="username" placeholder="Username" value="{{ old('username') }}">

    <label>No. Telp</label>
    <input type="text" name="telephone" placeholder="08xxxxxxxxxx" value="{{ old('telephone') }}">

    <label>Password</label>
    <div class="password">
      <input type="password" name="password" placeholder="Password" class="pwd-input">
      <i class="fa-solid fa-eye-slash pwd-toggle"></i>
    </div>

    <label>Alamat</label>
    <input type="text" name="alamat" placeholder="Alamat">

    <!-- NIS & Kelas -->
    <div class="row-input">
      <div>
        <label>NIS</label>
        <input type="text" name="nis_nisn" placeholder="NIS" value="{{ old('nis_nisn') }}">
      </div>
      <div>
        <label>Kelas</label>
        <input type="text" name="kelas" placeholder="Kelas" value="{{ old('kelas') }}">
      </div>
    </div>

    <div class="remember">
      <label>
        <input type="checkbox" name="remember">
        Ingat Saya
      </label>
    </div>

    <button type="submit">Daftar</button>

    <p class="register">
      Sudah Memiliki Akun ?
      <a href="{{ route('login') }}">Masuk</a>
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

const photoInput = document.getElementById('photo');
const previewImage = document.getElementById('previewImage');
const cameraIcon = document.getElementById('cameraIcon');

photoInput.addEventListener('change', function() {
    const file = this.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
            cameraIcon.style.display = 'none';
        }

        reader.readAsDataURL(file);
    }
});

</script>

</body>
</html>