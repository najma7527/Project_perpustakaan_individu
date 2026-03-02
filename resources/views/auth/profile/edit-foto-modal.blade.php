<div class="overlay" id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:9999;">
    <div class="modal" style="background:white; width:90%; max-width:400px; border-radius:12px; padding:2rem; box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h2 style="font-size:1.5rem;">Edit Foto Profile</h2>
            <span class="close-icon" onclick="closeModal()" style="font-size:2rem; line-height:1; cursor:pointer; color:#6c757d;">&times;</span>
        </div>
        <form action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Preview gambar -->
            <div style="text-align: center; margin-bottom:1.5rem;">
                <img id="previewFoto" src="#" alt="Preview" style="max-width:100%; max-height:200px; border-radius:8px; display:none; border:1px solid #e9ecef;">
            </div>

            <div class="upload-box" style="border:2px dashed #e9ecef; border-radius:12px; padding:1.5rem; text-align:center; background:#f8fafc; margin-bottom:1.5rem;">
                <input type="file" name="profile_photo" id="fotoInput" accept="image/*" required style="display:block; width:100%; margin-bottom:0.5rem;">
                <small style="color:#6c757d;">Klik atau drag foto baru</small>
            </div>

            <div class="btn-wrapper" style="text-align:right;">
                <button type="submit" class="btn-save" style="background:#004D40; color:white; border:none; padding:0.6rem 1.8rem; border-radius:30px; cursor:pointer;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview gambar sebelum upload
    document.getElementById('fotoInput')?.addEventListener('change', function(e) {
        const preview = document.getElementById('previewFoto');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    function openModal() {
        document.getElementById('modalOverlay').style.display = 'flex';
        // reset preview
        document.getElementById('previewFoto').style.display = 'none';
        document.getElementById('fotoInput').value = '';
    }

    function closeModal() {
        document.getElementById('modalOverlay').style.display = 'none';
    }
</script>