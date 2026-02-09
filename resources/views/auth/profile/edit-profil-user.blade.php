<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil User</title>
    <link rel="stylesheet" href="{{ asset('css/siswa/edit-profil-user.css') }}">
</head>
<body>

<div class="profile-container">
    <h2 class="title">Edit profil user</h2>

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
                <label>Jenis kelamin :</label>
                <input type="text" placeholder="Masukan Jenis kelamin">
                <small class="error">ⓘ Jenis Kelamin wajib diisi!</small>
            </div>

            <div class="form-group">
                <label>NISN :</label>
                <input type="text" placeholder="Masukan NISN">
                <small class="error">ⓘ NISN wajib diisi!</small>
            </div>

            <div class="form-group">
                <label>Kelas :</label>
                <input type="text" placeholder="Masukan Kelas">
                <small class="error">ⓘ Kelas wajib diisi!</small>
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

</body>
</html>
