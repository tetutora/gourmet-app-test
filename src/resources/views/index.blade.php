@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-end">
            <form action="{{ route('index') }}" method="GET" id="searchForm">
                <div class="input-group">
                    <select name="region_id" class="form-control" id="regionSelect">
                        <option value="">All area</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="genre_id" class="form-control" id="genreSelect">
                        <option value="">All genre</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" name="query" class="form-control" placeholder="Search" value="{{ request('query') }}" id="queryInput">
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
                    <p class="card-title">{{ $restaurant->name }}</p>
                    <p class="card-text">
                        <span class="badge bg-secondary">#{{ $restaurant->region->name }}</span>
                        <span class="badge bg-secondary">#{{ $restaurant->genre->name }}</span>
                    </p>
                    <a href="{{ route('restaurants.detail', $restaurant->id) }}" class="btn btn-primary btn-sm btn-detail">詳しく見る</a>
                </div>
                @auth
                <button class="btn-favorite" data-restaurant-id="{{ $restaurant->id }}">
                    <i class="{{ $favorites->contains($restaurant->id) ? 'fas' : 'far' }} fa-heart {{ $favorites->contains($restaurant->id) ? 'text-danger' : '' }}"></i>
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
        function autoSubmitForm() {
            document.getElementById('searchForm').submit();
        }

        document.getElementById('regionSelect').addEventListener('change', autoSubmitForm);
        document.getElementById('genreSelect').addEventListener('change', autoSubmitForm);
        document.getElementById('queryInput').addEventListener('input', autoSubmitForm);
    });

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
