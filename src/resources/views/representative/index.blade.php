@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative/index.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>登録店舗一覧</h1>

    @if($restaurants->isEmpty())
        <p>店舗が登録されていません。</p>
    @else
        <div class="restaurant-list">
            @foreach ($restaurants as $restaurant)
                <div class="restaurant-card">
                    <img src="{{ Str::startsWith($restaurant->image_url, ['http://', 'https://']) ? $restaurant->image_url : asset('storage/' . $restaurant->image_url) }}" alt="{{ $restaurant->name }}">
                    <div class="restaurant-card-body">
                        <h2>{{ $restaurant->name }}</h2>
                        <div class="restaurant-tags">
                            <span class="badge bg-secondary">#{{ $restaurant->region->name }}</span>
                            @foreach($restaurant->genres as $genre)
                                <span class="badge bg-secondary">#{{ $genre->name }}</span>
                            @endforeach
                        </div>
                        <p class="restaurant-description">{{ $restaurant->description }}</p>
                        <a href="{{ route('restaurants.edit', ['restaurant' => $restaurant->id]) }}" class="edit-link">編集</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
