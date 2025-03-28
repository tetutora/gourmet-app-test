@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        @foreach($restaurants as $restaurant)
        <div class="col-12 col-md-3 mb-4">
            <div class="card h-100 shadow-custom">
                <img src="{{ $restaurant->image_url }}" class="card-img-top" alt="{{ $restaurant->name }}">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $restaurant->name }}</h5>
                    <p class="card-text">
                        <span class="badge bg-secondary">#{{ $restaurant->region }}</span>
                        <span class="badge bg-secondary">#{{ $restaurant->genre }}</span>
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
        document.querySelectorAll('.btn-favorite').forEach(button => {
            button.addEventListener('click', function() {
                const restaurantId = this.getAttribute('data-restaurant-id');
                const icon = this.querySelector('i');
                const isFavorite = icon.classList.contains('fas'); // 塗りつぶしならお気に入り

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
                        icon.classList.toggle('fas');  // 塗りつぶしハート
                        icon.classList.toggle('far');  // 枠線ハート
                        icon.classList.toggle('text-danger'); // 赤色の切り替え
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@endsection
