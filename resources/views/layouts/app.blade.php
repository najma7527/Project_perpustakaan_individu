<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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

                        <form action="{{ route('notif.readAll') }}" method="POST">
                            @csrf
                            <button type="submit" class="notif-readall"
                                {{ (($notifications ?? collect())->count() == 0) ? 'disabled' : '' }}>
                                Tandai Semua Dibaca
                            </button>
                        </form>
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
        @yield('content')
    </main>

</div>

{{-- ================= SCRIPT ================= --}}
<script>

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