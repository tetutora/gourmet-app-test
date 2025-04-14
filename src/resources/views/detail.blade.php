@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="restaurant-container">
    <div class="restaurant-left">
        <div class="restaurant-header">
            <a href="{{ route('index') }}" class="back-button">＜</a>
            <p class="restaurant-name">{{ $restaurant->name }}</p>
        </div>
        <img src="{{ Str::startsWith($restaurant->image_url, ['http://', 'https://']) ? $restaurant->image_url : asset('storage/' . $restaurant->image_url) }}" class="card-img-top" alt="{{ $restaurant->name }}">
        <div class="restaurant-tags">
            <span class="badge bg-secondary">#{{ $restaurant->region->name }}</span>
            @foreach($restaurant->genres as $genre)
                <span class="badge bg-secondary">#{{ $genre->name }}</span>
            @endforeach
        </div>
        <p class="restaurant-description">{{ $restaurant->description }}</p>
        <div class="review-section">
            <div class="review-header">
                <p class="review-title">レビュー</p>

                @if ($restaurant->reviews->count())
                    <p class="review-average">平均評価：<strong>{{ number_format($averageRating, 2) }}</strong> / 5</p>
                @else
                    <p class="review-average">レビューはまだありません。</p>
                @endif
            </div>

            {{-- レビュー一覧 --}}
            @if ($restaurant->reviews->count())
                <div class="review-list-container">
                    <ul class="review-list">
                        @foreach ($restaurant->reviews as $index => $review)
                            <li class="review-item {{ $index >= 2 ? 'hidden-review' : '' }}">
                                <div class="review-user"><strong>{{ $review->user->name }}</strong> さん</div>
                                <div class="review-stars">評価：{{ $review->rating }} / 5</div>
                                <div class="review-comment">「{{ $review->comment }}」</div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if ($restaurant->reviews->count() > 2)
                    <button id="show-more-button" class="show-more-button">もっと見る</button>
                @endif
            @endif
        </div>
    </div>

    <div class="restaurant-right">
        <p class="reservation-title">予約</p>
        <form action="{{ route('reservation.store') }}" method="POST" class="reservation-form">
            @csrf
            <input type="date" id="reservation_date" name="reservation_date">
            @error('reservation_date')
                <p class="error-message">{{ $message }}</p>
            @enderror
            <select id="reservation_time" name="reservation_time">
                @for ($hour = 9; $hour <= 23; $hour++)
                    <option value="{{ sprintf('%02d:00', $hour) }}">{{ sprintf('%02d:00', $hour) }}</option>
                    <option value="{{ sprintf('%02d:30', $hour) }}">{{ sprintf('%02d:30', $hour) }}</option>
                @endfor
            </select>
            @error('reservation_time')
                <p class="error-message">{{ $message }}</p>
            @enderror
            <input type="number" id="num_people" name="num_people" min="1">
            @error('num_people')
                <p class="error-message">{{ $message }}</p>
            @enderror
            <div class="reservation-summary">
                <p><strong>Shop</strong><span>{{ $restaurant->name }}</span></p>
                <p><strong>Date</strong><span id="selected-date">未選択</span></p>
                <p><strong>Time</strong><span id="selected-time">未選択</span></p>
                <p><strong>Number</strong><span id="selected-people">未選択</span></p>
                <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
            </div>
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

    const showMoreBtn = document.getElementById("show-more-button");
    if (showMoreBtn) {
        showMoreBtn.addEventListener("click", function () {
            document.querySelectorAll(".hidden-review").forEach(el => {
                el.style.display = "block";
            });
            showMoreBtn.style.display = "none";
        });
    }
</script>
@endsection

