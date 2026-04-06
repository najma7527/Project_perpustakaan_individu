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
        @error('photo_profile') <small class="error-text">{{ $message }}</small> @enderror

        <label>Nama Lengkap</label>
        <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}">
        @error('name') <small class="error-text">{{ $message }}</small> @enderror

        <label>Username</label>
        <input type="text" name="username" placeholder="Username" value="{{ old('username') }}">
        @error('username') <small class="error-text">{{ $message }}</small> @enderror

        <label>No. Telp</label>
        <input type="text" name="telephone" placeholder="08xxxxxxxxxx" value="{{ old('telephone') }}">
        @error('telephone') <small class="error-text">{{ $message }}</small> @enderror

        <label>Password</label>
        <div class="password">
          <input type="password" name="password" placeholder="Password" class="pwd-input">
          <i class="fa-solid fa-eye-slash pwd-toggle"></i>
        </div>
        @error('password') <small class="error-text">{{ $message }}</small> @enderror

        <label>Alamat</label>
        <input type="text" name="alamat" placeholder="Alamat" value="{{ old('alamat') }}">
        @error('alamat') <small class="error-text">{{ $message }}</small> @enderror

        <!-- NIS & Kelas -->
        <div class="row-input">
          <div>
            <label>NIS</label>
            <input type="text" name="nis_nisn" placeholder="NIS" value="{{ old('nis_nisn') }}">
            @error('nis_nisn') <small class="error-text">{{ $message }}</small> @enderror
          </div>
          <div>
            <label>Kelas</label>
            <input type="text" name="kelas" placeholder="Kelas" value="{{ old('kelas') }}">
            @error('kelas') <small class="error-text">{{ $message }}</small> @enderror
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  // Toastr tetap sama
  toastr.options = {
    "closeButton": true,
    "positionClass": "toast-top-right",
    "timeOut": "4000"
  };

  // Preview & validasi foto
  const photoInput = document.getElementById('photo');
  const previewImage = document.getElementById('previewImage');
  const cameraIcon = document.getElementById('cameraIcon');
  const MAX_FILE_SIZE = 2 * 1024 * 1024;
  const ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png'];

  photoInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
      if (!ALLOWED_TYPES.includes(file.type)) {
        toastr.error('Format file harus JPG, JPEG, atau PNG');
        this.value = '';
        previewImage.style.display = 'none';
        cameraIcon.style.display = 'block';
        return;
      }
      if (file.size > MAX_FILE_SIZE) {
        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        toastr.error(`Ukuran file ${sizeMB}MB melebihi batas maksimal 2MB`);
        this.value = '';
        previewImage.style.display = 'none';
        cameraIcon.style.display = 'block';
        return;
      }
      const reader = new FileReader();
      reader.onload = function(e) {
        previewImage.src = e.target.result;
        previewImage.style.display = 'block';
        cameraIcon.style.display = 'none';
        toastr.success('Foto berhasil dipilih');
      };
      reader.readAsDataURL(file);
    }
  });

  // Toggle password visibility
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