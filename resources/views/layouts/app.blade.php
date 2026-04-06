<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    @stack('styles')

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app-container">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- ================= TOPBAR ================= --}}
    <div class="topbar">

        {{-- KIRI --}}
        <div class="topbar-left">
            @if(Auth::user()->role === 'anggota')
                <div class="notification-wrapper">
                    <button class="notif-btn" onclick="toggleNotif(event)">
                        <i class="fas fa-bell"></i>

                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="notif-badge">{{ $unreadCount }}</span>
                        @endif
                    </button>

                    <div class="notif-dropdown" id="notifDropdown">
                        @forelse($notifications ?? [] as $notif)
                            @if(!$notif->is_read)
                                <div class="notif-item fw-bold" onclick="markNotificationAsRead(event, {{ $notif->id }})">

                                    @if($notif->type == 'danger')
                                        🔴
                                    @elseif($notif->type == 'warning')
                                        ⚠️
                                    @elseif($notif->type == 'success')
                                        ✅
                                    @else
                                        ℹ️
                                    @endif

                                    {{ $notif->message }}
                                    <small>{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                            @endif
                        @empty
                            <div class="notif-empty">
                                Tidak ada notifikasi
                            </div>
                        @endforelse

                        <!-- <form action="{{ route('notif.readAll') }}" method="POST" id="readAllForm">
                            @csrf
                            <button type="submit" class="notif-readall"
                                {{ (($unreadCount ?? 0) == 0) ? 'disabled' : '' }}>
                                Tandai Semua Dibaca
                            </button>
                        </form> -->
                    </div>
                </div>
            @endif
        </div>

        {{-- KANAN --}}
        <div class="topbar-right">

            <div class="user">
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <small class="user-role">{{ ucfirst(Auth::user()->role) }}</small>
                </div>

                <div class="user-wrapper">
                    <div class="user-trigger" onclick="toggleUserPopup(event)">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ Storage::url(Auth::user()->profile_photo) }}" class="avatar">
                        @else
                            <div class="avatar-default">
                                <i class="fa fa-user"></i>
                            </div>
                        @endif
                    </div>

                    <div class="user-popup" id="userPopup">
                        <div class="popup-header">

                            @if(Auth::user()->profile_photo)
                                <img src="{{ Storage::url(Auth::user()->profile_photo) }}" class="avatar">
                            @else
                                <div class="avatar-default">
                                    <i class="fa fa-user"></i>
                                </div>
                            @endif
                            <div class="popup-user-info">
                                <strong>{{ Auth::user()->name }}</strong>
                                <small>{{ Auth::user()->username }}</small>
                            </div>
                        </div>

                        <a href="{{ route('profile.show') }}" class="btn-profile">
                            <i class="fa fa-user"></i> Profile Saya
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="fa fa-sign-out"></i> Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>
    {{-- ================= END TOPBAR ================= --}}

    {{-- CONTENT --}}
    <main class="content">
        <div class="fade-in-up">
            @yield('content')
        </div>
    </main>

</div>

{{-- ================= SCRIPT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Konfigurasi Toastr
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

// Tampilkan notifikasi dari Flash Messages
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif
    
    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
    
    @if(session('warning'))
        toastr.warning('{{ session('warning') }}');
    @endif
    
    @if(session('info'))
        toastr.info('{{ session('info') }}');
    @endif
});

// Fungsi konfirmasi dengan SweetAlert
function confirmAction(message, callback) {
    Swal.fire({
        title: 'Konfirmasi',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// Fungsi sukses dengan SweetAlert
function showSuccess(message, redirectUrl = null) {
    Swal.fire({
        title: 'Berhasil!',
        text: message,
        icon: 'success',
        confirmButtonColor: '#3085d6',
    }).then((result) => {
        if (redirectUrl && result.isConfirmed) {
            window.location.href = redirectUrl;
        }
    });
}
function toggleUserPopup(event) {
    event.stopPropagation();
    document.getElementById('userPopup').classList.toggle('show');
}

document.addEventListener('click', function (e) {
    const popup = document.getElementById('userPopup');
    const wrapper = document.querySelector('.user-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        popup.classList.remove('show');
    }
});

function toggleNotif(event) {
    event.stopPropagation();
    document.getElementById('notifDropdown').classList.toggle('show');
}

// Handle klik notifikasi individual
function markNotificationAsRead(event, notifId) {
    event.preventDefault();
    event.stopPropagation();
    
    const token = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = token ? token.getAttribute('content') : '';
    
    fetch(`/profile/notif/read/${notifId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hapus elemen notifikasi
            event.currentTarget.remove();
            
            // Update badge count
            const badge = document.querySelector('.notif-badge');
            if (badge) {
                const count = parseInt(badge.textContent) - 1;
                if (count > 0) {
                    badge.textContent = count;
                } else {
                    badge.remove();
                }
            }
            
            // Cek apakah semua notifikasi sudah dibaca
            if (document.querySelectorAll('.notif-item').length === 0) {
                const dropdown = document.getElementById('notifDropdown');
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'notif-empty';
                emptyDiv.textContent = 'Tidak ada notifikasi';
                dropdown.insertBefore(emptyDiv, dropdown.querySelector('#readAllForm'));
                
                // Disable tombol
                document.getElementById('readAllForm').querySelector('button').disabled = true;
            }
            
            toastr.success(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Jika AJAX gagal, fallback ke regular link
        window.location.href = `/profile/notif/read/${notifId}`;
    });
}

// Handle "Tandai Semua Dibaca"
document.addEventListener('DOMContentLoaded', function() {
    const readAllForm = document.getElementById('readAllForm');
    if (readAllForm) {
        readAllForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const csrfToken = this.querySelector('input[name="_token"]').value;
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hapus semua notifikasi dari dropdown
                    document.querySelectorAll('.notif-item').forEach(item => item.remove());
                    
                    // Hapus badge
                    const badge = document.querySelector('.notif-badge');
                    if (badge) badge.remove();
                    
                    // Disable tombol
                    readAllForm.querySelector('button').disabled = true;
                    
                    // Tampilkan pesan kosong
                    const dropdown = document.getElementById('notifDropdown');
                    if (!dropdown.querySelector('.notif-empty')) {
                        const emptyDiv = document.createElement('div');
                        emptyDiv.className = 'notif-empty';
                        emptyDiv.textContent = 'Tidak ada notifikasi';
                        dropdown.insertBefore(emptyDiv, readAllForm);
                    }
                    
                    toastr.success(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Terjadi kesalahan saat memproses notifikasi');
            });
        });
    }
});

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notifDropdown');
    const wrapper = document.querySelector('.notification-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});
</script>

</body>
</html>