<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
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
    <ul class="header-nav">
        @if (Auth::check())
            <!-- ログイン済みの場合 -->
        @else
            <!-- ログインしていない場合 -->
        @endif
    </ul>
    <main>
        @yield('content')
    </main>
</body>
</html>
