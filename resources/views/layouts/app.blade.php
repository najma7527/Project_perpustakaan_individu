<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    {{-- CSS GLOBAL --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- CSS TAMBAHAN PER HALAMAN --}}
    @stack('styles')

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app-container">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- TOPBAR --}}
<div class="topbar">

    <!-- 🔔 NOTIFIKASI -->
<div class="notification-wrapper">
    <div class="dropdown">
        <button class="notif-btn" onclick="toggleNotif(event)">
            <i class="fas fa-bell"></i>

            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="notif-badge">{{ $unreadCount }}</span>
            @endif
        </button>

        <div class="notif-dropdown" id="notifDropdown">
            <!-- Daftar Notifikasi -->
            @forelse($notifications ?? [] as $notif)
                <a href="{{ route('notif.read', $notif->id) }}" 
                   class="notif-item {{ $notif->is_read ? '' : 'fw-bold' }}">

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
                </a>
            @empty
                <div class="notif-empty">
                    Tidak ada notifikasi
                </div>
            @endforelse

            <!-- Tombol Tandai Semua Dibaca -->
            <form action="{{ route('notif.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="notif-readall" 
                    {{ (($notifications ?? collect())->count() == 0) ? 'disabled' : '' }}>
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .notif-readall:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

    <div class="user">
        <div class="user-info">
            <span class="user-name">{{ Auth::user()->name }}</span>
            <small class="user-role">{{ ucfirst(Auth::user()->role) }}</small>
        </div>

        <div class="user-wrapper">
            <div class="user-trigger" onclick="toggleUserPopup(event)">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset(Auth::user()->profile_photo) }}" class="avatar">
                @else
                    <div class="avatar-default">
                        <i class="fa fa-user"></i>
                    </div>
                @endif
            </div>

            <!-- POPUP -->
            <div class="user-popup" id="userPopup">
                <div class="popup-header">
                    @if(Auth::user()->profile_photo)
                    <img src="{{ asset(Auth::user()->profile_photo) }}" class="avatar">
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

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa fa-sign-out"></i> Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>



    {{-- CONTENT --}}
    <main class="content">
        @yield('content')
    </main>

</div>

{{-- TOAST GLOBAL --}}
@if(session('success'))
<div class="toast success" id="toast">
    <i class="fa-solid fa-circle-check"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="toast error" id="toast">
    <i class="fa-solid fa-circle-xmark"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

@if(session('warning'))
<div class="toast warning" id="toast">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <span>{{ session('warning') }}</span>
</div>
@endif

{{-- SCRIPT GLOBAL --}}
<script>
function toggleUserPopup(event) {
    event.stopPropagation();
    const popup = document.getElementById('userPopup');
    popup.classList.toggle('show');
}

// Tutup popup saat klik di luar
document.addEventListener('click', function (e) {
    const popup = document.getElementById('userPopup');
    const userWrapper = document.querySelector('.user-wrapper');

    if (!userWrapper.contains(e.target)) {
        popup.classList.remove('show');
    }
});

// Tutup popup saat klik menu
document.querySelectorAll('.btn-profile').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('userPopup').classList.remove('show');
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const toast = document.getElementById("toast");
    if (toast) {

        setTimeout(() => {
            toast.classList.add("show");
        }, 200);

        setTimeout(() => {
            toast.classList.remove("show");
        }, 4000);
    }
});

function toggleNotif(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('notifDropdown');
    dropdown.classList.toggle('show');
}

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
