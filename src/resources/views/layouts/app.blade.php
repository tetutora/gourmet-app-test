<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/popup.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>gourmet-app</title>
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a href="javascript:void(0);" id="menu-toggle">
                <i class="fas fa-list"></i>
            </a>
            <span>Rese</span>
        </div>
    </header>

    <div id="popup" class="popup">
        <div class="popup-content">
            <button id="close-popup" class="close-btn">×</button>

            @auth
                @php
                    $roleId = Auth::user()->role_id ?? null;
                @endphp

                @if ($roleId === $RoleType::ROLE_USER)
                    <p><a href="{{ url('/') }}">Home</a></p>
                    <p><a href="{{ url('/mypage') }}">Mypage</a></p>
                    <p><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></p>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                @elseif ($roleId === $RoleType::ROLE_REPRESENTATIVE)
                    <p><a href="{{ route('representative.dashboard') }}">店舗予約情報</a></p>
                    <p><a href="{{ route('representative.create') }}">店舗情報作成</a></p>
                    <p><a href="{{ route('representative.index') }}">店舗一覧</a></p>
                    <p><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></p>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                @elseif ($roleId === $RoleType::ROLE_ADMIN)
                    <p><a href="{{ route('administrator.dashboard') }}">店舗代表者一覧</a></p>
                    <p><a href="{{ route('administrator.create') }}">店舗代表者作成</a></p>
                    <p><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></p>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endif

            @else
                <p><a href="{{ url('/') }}">Home</a></p>
                <p><a href="{{ route('register') }}">Registration</a></p>
                <p><a href="{{ route('login') }}">Login</a></p>
            @endauth
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('popup').style.display = 'flex';
        });

        document.getElementById('close-popup').addEventListener('click', function() {
            document.getElementById('popup').style.display = 'none';
        });
    </script>
</body>
</html>
