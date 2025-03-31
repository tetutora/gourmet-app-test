@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="restaurant-container">
    <!-- 左側の店舗詳細 -->
    <div class="restaurant-left">
        <div class="restaurant-header">
            <a href="{{ route('index') }}" class="back-button">＜</a>
            <h2>{{ $restaurant->name }}</h2>
        </div>
        <img src="{{ $restaurant->image_url }}" class="restaurant-image" alt="{{ $restaurant->name }}">
        <div class="restaurant-tags">
            <span class="badge bg-secondary">#{{ $restaurant->region->name }}</span>
            <span class="badge bg-secondary">#{{ $restaurant->genre->name }}</span>
        </div>
        <p class="restaurant-description">{{ $restaurant->description }}</p>
    </div>

    <!-- 右側の空白部分 -->
    <div class="restaurant-right"></div>
</div>
@endsection
