<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/popup.css') }}"> <!-- 追加 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        @if(request()->routeIs('index'))
        <div class="search-container">
            <input type="text" class="search-input" placeholder="検索">
        </div>
        @endif
    </header>

    <div id="popup" class="popup">
        <div class="popup-content">
            <button id="close-popup" class="close-btn">×</button>
            @if (Auth::check())
                <p><a href="{{ url('/') }}">Home</a></p>
                <p><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></p>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <p><a href="{{ url('/mypage') }}">Mypage</a></p>
            @else
                <p><a href="{{ url('/') }}">Home</a></p>
                <p><a href="{{ route('register') }}">Registration</a></p>
                <p><a href="{{ route('login') }}">Login</a></p>
            @endif
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
