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

    <!-- 右側の予約画面 -->
    <div class="restaurant-right">
        <h2 class="reservation-title">予約</h2>
        <form action="{{ route('reservation.store') }}" method="POST" class="reservation-form">
            @csrf
            <!-- 日付入力 -->
            <label for="date">日付</label>
            <input type="date" id="date" name="date" required>

            <!-- 時間入力 -->
            <label for="time">時間</label>
            <select id="time" name="time" required>
                @for ($hour = 10; $hour <= 22; $hour++)
                    <option value="{{ sprintf('%02d:00', $hour) }}">{{ sprintf('%02d:00', $hour) }}</option>
                    <option value="{{ sprintf('%02d:30', $hour) }}">{{ sprintf('%02d:30', $hour) }}</option>
                @endfor
            </select>

            <!-- 予約人数 -->
            <label for="people">人数</label>
            <input type="number" id="people" name="people" min="1" max="10" required>

            <!-- 予約情報表示 -->
            <div class="reservation-summary">
                <p>店舗名: {{ $restaurant->name }}</p>
                <p>予約日: <span id="selected-date"></span></p>
                <p>予約時間: <span id="selected-time"></span></p>
                <p>予約人数: <span id="selected-people"></span>人</p>
            </div>

            <!-- 予約ボタン -->
            <button type="submit" class="reservation-button">予約する</button>
        </form>
    </div>
</div>
@endsection
