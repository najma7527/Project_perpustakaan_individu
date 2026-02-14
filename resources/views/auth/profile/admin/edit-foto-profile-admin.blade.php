<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Foto Profile</title>
    <link rel="stylesheet" href="{{ asset('css/admin/edit-foto-profile-admin.css') }}">
</head>
<body>

<div class="overlay" id="modalOverlay">
    <div class="modal">

        {{-- HEADER --}}
        <div class="modal-header">
            <h2>Edit Foto Profile</h2>
            <span class="close-icon" onclick="closeModal()">×</span>
        </div>
        <hr>

        {{-- UPLOAD --}}
        <div class="upload-box">
            <div class="upload-content">
                <div class="icon">🖼️+</div>
                <p class="text">Masukkan foto</p>
                <span class="info">PNG, JPEG (max 200 x 200)</span>
            </div>
            <input type="file" class="file-input">
        </div>

        {{-- BUTTON --}}
        <div class="btn-wrapper">
            <button type="button" class="btn-close" onclick="closeModal()">Tutup</button>
        </div>

    </div>
</div>

<script>
function closeModal() {
    document.getElementById('modalOverlay').style.display = 'none';
}
</script>

</body>
</html>
