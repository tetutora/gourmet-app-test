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
            <input type="date" id="reservation_date" name="reservation_date" required>

            <!-- 時間入力 -->
            <select id="reservation_time" name="reservation_time" required>
                @for ($hour = 9; $hour <= 23; $hour++)
                    <option value="{{ sprintf('%02d:00', $hour) }}">{{ sprintf('%02d:00', $hour) }}</option>
                    <option value="{{ sprintf('%02d:30', $hour) }}">{{ sprintf('%02d:30', $hour) }}</option>
                @endfor
            </select>

            <!-- 予約人数 -->
            <input type="number" id="num_people" name="num_people" min="1" max="10" required>

            <!-- 予約情報表示 -->
            <div class="reservation-summary">
                <p>Shop {{ $restaurant->name }}</p>
                <p>Date <span id="selected-date"></span></p>
                <p>Time <span id="selected-time"></span></p>
                <p>Number <span id="selected-people"></span></p>
                <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
            </div>

            <!-- 予約ボタン -->
            <button type="submit" class="reservation-button">予約する</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dateInput = document.getElementById("reservation_date");
        const timeInput = document.getElementById("reservation_time");
        const peopleInput = document.getElementById("num_people");

        const selectedDate = document.getElementById("selected-date");
        const selectedTime = document.getElementById("selected-time");
        const selectedPeople = document.getElementById("selected-people");

        dateInput.addEventListener("change", function() {
            selectedDate.textContent = dateInput.value || "未選択";
        });

        timeInput.addEventListener("change", function() {
            selectedTime.textContent = timeInput.value || "未選択";
        });

        peopleInput.addEventListener("change", function() {
            selectedPeople.textContent = peopleInput.value || "未選択";
        });

        selectedDate.textContent = dateInput.value || "未選択";
        selectedTime.textContent = timeInput.value || "未選択";
        selectedPeople.textContent = peopleInput.value || "未選択";
    });
</script>
@endsection
