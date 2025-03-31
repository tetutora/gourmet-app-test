@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <!-- 検索フォーム -->
        <div class="col-12">
            <form action="{{ route('index') }}" method="GET" id="searchForm">
                <div class="row">
                    <!-- 地域選択 -->
                    <div class="col-md-4 mb-3">
                        <select name="region_id" class="form-control" id="regionSelect">
                            <option value="">地域を選択</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ジャンル選択 -->
                    <div class="col-md-4 mb-3">
                        <select name="genre_id" class="form-control" id="genreSelect">
                            <option value="">ジャンルを選択</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- キーワード検索 -->
                    <div class="col-md-4 mb-3">
                        <input type="text" name="query" class="form-control" placeholder="店舗名で検索" value="{{ request('query') }}" id="queryInput">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @foreach($restaurants as $restaurant)
        <div class="col-12 col-md-3 mb-4">
            <div class="card h-100 shadow-custom">
                <img src="{{ $restaurant->image_url }}" class="card-img-top" alt="{{ $restaurant->name }}">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $restaurant->name }}</h5>
                    <p class="card-text">
                        <span class="badge bg-secondary">#{{ $restaurant->region->name }}</span>
                        <span class="badge bg-secondary">#{{ $restaurant->genre->name }}</span>
                    </p>
                    <a href="{{ route('restaurants.detail', $restaurant->id) }}" class="btn btn-primary btn-sm btn-detail">詳しく見る</a>
                </div>
                @auth
                <button class="btn-favorite" data-restaurant-id="{{ $restaurant->id }}">
                    <i class="{{ in_array($restaurant->id, $favorites) ? 'fas' : 'far' }} fa-heart {{ in_array($restaurant->id, $favorites) ? 'text-danger' : '' }}"></i>
                </button>
                @else
                <button class="btn-favorite" disabled>
                    <i class="far fa-heart"></i>
                </button>
                @endauth
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 入力変更時に自動でフォームを送信
        function autoSubmitForm() {
            document.getElementById('searchForm').submit();
        }

        // イベントリスナーを追加
        document.getElementById('regionSelect').addEventListener('change', autoSubmitForm);
        document.getElementById('genreSelect').addEventListener('change', autoSubmitForm);
        document.getElementById('queryInput').addEventListener('input', autoSubmitForm);
    });

    // お気に入りボタンの処理
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.btn-favorite').forEach(button => {
            button.addEventListener('click', function() {
                const restaurantId = this.getAttribute('data-restaurant-id');
                const icon = this.querySelector('i');
                const isFavorite = icon.classList.contains('fas');

                const url = isFavorite
                    ? `/favorites/remove/${restaurantId}`
                    : `/favorites/add/${restaurantId}`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ restaurant_id: restaurantId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        icon.classList.toggle('fas');
                        icon.classList.toggle('far');
                        icon.classList.toggle('text-danger');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@endsection
