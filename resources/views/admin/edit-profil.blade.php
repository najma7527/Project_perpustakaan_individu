<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/edit-profil.css') }}">
</head>
<body>

<div class="profile-container">
    <h2 class="title">Edit profil Admin</h2>

    <form>
        @csrf

        <div class="form-grid">
            <!-- KIRI -->
            <div class="form-group">
                <label>Nama :</label>
                <input type="text" placeholder="Masukan Nama">
                <small class="error">ⓘ Nama wajib diisi!</small>
            </div>

            <!-- KANAN -->
            <div class="form-group">
                <label>Username :</label>
                <input type="text" placeholder="Masukan Username">
                <small class="error">ⓘ Username wajib diisi!</small>
            </div>

            <div class="form-group">
                <label>No. Telepon :</label>
                <input type="text" placeholder="Masukan No. Telepon">
                <small class="error">ⓘ No. Telepon wajib diisi!</small>
            </div>
        </div>

        <div class="btn-wrapper">
            <button type="submit" class="btn-save">Simpan</button>
        </div>
    </form>
</div>

<script>
    const form = document.getElementById('profileForm');
    const nama = document.getElementById('nama');
    const username = document.getElementById('username');

    const namaError = document.getElementById('namaError');
    const usernameError = document.getElementById('usernameError');

    function validateNama() {
        if (nama.value.trim() === '') {
            namaError.style.display = 'block';
            nama.style.border = '1px solid red';
            return false;
        } else {
            namaError.style.display = 'none';
            nama.style.border = '1px solid transparent';
            return true;
        }
    }

    function validateUsername() {
        if (username.value.trim() === '') {
            usernameError.style.display = 'block';
            username.style.border = '1px solid red';
            return false;
        } else {
            usernameError.style.display = 'none';
            username.style.border = '1px solid transparent';
            return true;
        }
    }

    // realtime saat mengetik
    nama.addEventListener('input', validateNama);
    username.addEventListener('input', validateUsername);

    // saat submit
    form.addEventListener('submit', function(e) {
        if (!validateNama() | !validateUsername()) {
            e.preventDefault();
        }
    });
</script>

</body>
</html>
