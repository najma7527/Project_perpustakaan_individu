
function openDetailModal(user) {

    const detailModal = document.getElementById('detailModal');
    if (!detailModal) return;

    detailModal.style.display = 'flex';

    document.getElementById('detail_name').textContent = user.name ?? '-';
    document.getElementById('detail_username').textContent = user.username ?? '-';
    document.getElementById('detail_nis').textContent = user.nis_nisn ?? '-';
    document.getElementById('detail_kelas').textContent = user.kelas ?? '-';
    document.getElementById('detail_telephone').textContent = user.telephone ?? '-';

    let statusBadge = '';

switch (user.status) {
    case 'aktif':
        statusBadge = '<span class="badge badge-success">Aktif</span>';
        break;

    case 'nonaktif':
        statusBadge = '<span class="badge badge-danger">Nonaktif</span>';
        break;

    case 'menunggu':
        statusBadge = '<span class="badge badge-warning">Menunggu</span>';
        break;

    case 'ditolak':
        statusBadge = '<span class="badge badge-dark">Ditolak</span>';
        break;

    default:
        statusBadge = '<span class="badge badge-secondary">Tidak Diketahui</span>';
}


    document.getElementById('detail_status').innerHTML = statusBadge;

    document.getElementById('detail_date').textContent =
        user.created_at
            ? new Date(user.created_at).toLocaleDateString('id-ID')
            : '-';
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
window.addEventListener('click', function(event) {
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
