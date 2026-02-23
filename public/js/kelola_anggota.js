
function openDetailModal(user) {

    const detailModal = document.getElementById('detailModal');
    if (!detailModal) return;

    detailModal.style.display = 'flex';

    // Foto profil
    const photoEl = document.getElementById('detail_photo');
    if (photoEl) {
        photoEl.src = user.profile_photo || '/img/profile.png';
    }

    document.getElementById('detail_name').textContent = user.name ?? '-';
    document.getElementById('detail_username').textContent = '@' + (user.username ?? '-');
    document.getElementById('detail_nis').textContent = user.nis_nisn ?? '-';
    document.getElementById('detail_telephone').textContent = user.telephone ?? '-';
    document.getElementById('detail_status').textContent = user.status ? user.status.charAt(0).toUpperCase() + user.status.slice(1) : '-';

    const alamatEl = document.getElementById('detail_alamat');
    if (alamatEl) {
        alamatEl.textContent = user.alamat ?? '-';
    }

    // Set link cetak kartu
    const cetakBtn = document.getElementById('detail_cetak_btn');
    if (cetakBtn && user.id) {
        cetakBtn.setAttribute('data-url', '/admin/anggota/kartu/' + user.id + '/export');
        cetakBtn.onclick = function (e) {
            e.preventDefault();
            downloadKartu(this.getAttribute('data-url'));
        };
    }
}

// Download Kartu via Fetch (tanpa navigasi halaman)
function downloadKartu(url) {
    const btn = document.getElementById('detail_cetak_btn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengunduh...';
    btn.style.pointerEvents = 'none';

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengunduh kartu');
            const disposition = response.headers.get('Content-Disposition');
            let filename = 'kartu-anggota.pdf';
            if (disposition && disposition.indexOf('filename=') !== -1) {
                filename = disposition.split('filename=')[1].replace(/"/g, '').trim();
            }
            return response.blob().then(blob => ({ blob, filename }));
        })
        .then(({ blob, filename }) => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        })
        .catch(error => {
            alert(error.message || 'Terjadi kesalahan saat mengunduh kartu.');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.style.pointerEvents = '';
        });
}


// Close Detail Modal
function closeDetailModal() {
    const detailModal = document.getElementById('detailModal');
    if (detailModal) {
        detailModal.style.display = 'none';
    }
}

// Open Edit Modal dengan data user
function openEditModal(id, name, username, nis, kelas) {
    const editModal = document.getElementById('editModal');
    if (!editModal) {
        console.error('Edit modal not found');
        return;
    }

    editModal.style.display = 'flex';

    // Set display fields
    const editName = document.getElementById('edit_name');
    const editUsername = document.getElementById('edit_username');
    const editNis = document.getElementById('edit_nis');
    const editKelas = document.getElementById('edit_kelas');

    if (editName) editName.value = name;
    if (editUsername) editUsername.value = username;
    if (editNis) editNis.value = nis || '-';
    if (editKelas) editKelas.value = kelas || '-';

    // Set form actions untuk kedua form
    const statusUrl = '/admin/anggota/' + id;
    const passwordUrl = '/admin/anggota/' + id + '/reset-password';

    const formEditStatus = document.getElementById('formEditStatus');
    const formResetPassword = document.getElementById('formResetPassword');

    if (formEditStatus) formEditStatus.action = statusUrl;
    if (formResetPassword) formResetPassword.action = passwordUrl;
}

// Close Modal
function closeModal() {
    const editModal = document.getElementById('editModal');
    if (editModal) {
        editModal.style.display = 'none';

        // Reset forms
        const formEditStatus = document.getElementById('formEditStatus');
        const formResetPassword = document.getElementById('formResetPassword');

        if (formEditStatus) formEditStatus.reset();
        if (formResetPassword) formResetPassword.reset();

        // Kembali ke tab pertama
        switchTab(null, 'tab-data');
    }
}

// Switch Tab dalam Modal
function switchTab(event, tabName) {
    if (event) {
        event.preventDefault();
    }

    // Hide semua tabs
    const tabs = document.querySelectorAll('.modal-tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));

    // Remove active class dari semua buttons
    const buttons = document.querySelectorAll('.modal-tab-btn');
    buttons.forEach(btn => btn.classList.remove('active'));

    // Show selected tab
    const selectedTab = document.getElementById(tabName);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }

    // Add active class ke button yang diklik
    if (event && event.target) {
        const btn = event.target.closest('.modal-tab-btn');
        if (btn) btn.classList.add('active');
    } else {
        const activeBtn = document.querySelector('[data-tab="' + tabName + '"]');
        if (activeBtn) {
            activeBtn.classList.add('active');
        }
    }
}

// Close modal ketika klik luar
window.addEventListener('click', function (event) {
    const editModal = document.getElementById('editModal');
    const detailModal = document.getElementById('detailModal');

    if (editModal && event.target === editModal) {
        closeModal();
    }
    if (detailModal && event.target === detailModal) {
        closeDetailModal();
    }
});

// Confirm Delete dengan custom popup
function confirmDelete(userId, userName) {
    if (confirm(`Apakah Anda yakin ingin menghapus anggota "${userName}"?`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/anggota/${userId}`;
        form.submit();
    }
}
