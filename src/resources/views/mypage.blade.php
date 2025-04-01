@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="mypage-content">
        <!-- 左側: 予約状況 -->
        <div class="mypage-left">
            <h3>予約状況</h3>
            <div class="reservations">
                @foreach ($reservations as $reservation)
                    <div class="reservation-item">
                        <div class="reservation-header">
                            <p><strong><i class="fas fa-clock"></i></strong>予約{{ $loop->iteration }}</p>
                            <!-- 予約取り消しの×マーク -->
                            <button class="cancel-reservation" data-id="{{ $reservation->id }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p><strong>Shop</strong> {{ $reservation->restaurant->name }}</p>
                        <p><strong>Date</strong> {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y-m-d') }}</p>
                        <p><strong>Time</strong> {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</p>
                        <p><strong>Number</strong> {{ $reservation->num_people }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 右側: お気に入り店舗 -->
        <div class="mypage-right">
            <h3>お気に入り店舗</h3>
            <div class="row">
                @foreach ($favorites as $favorite)
                    <div class="col">
                        <div class="card shadow-custom">
                            <img src="{{ $favorite->restaurant->image_url }}" class="card-img-top" alt="{{ $favorite->restaurant->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $favorite->restaurant->name }}</h5>
                                <p class="card-text">
                                    <span class="badge bg-secondary">#{{ $favorite->restaurant->region->name }}</span>
                                    <span class="badge bg-secondary">#{{ $favorite->restaurant->genre->name }}</span>
                                </p>
                                <a href="{{ route('restaurants.detail', $favorite->restaurant->id) }}" class="btn-detail">詳細を見る</a>
                            </div>
                            @auth
                            <button class="btn-favorite" data-restaurant-id="{{ $favorite->restaurant->id }}">
                                <i class="{{ in_array($favorite->restaurant->id, $favoriteIds) ? 'fas' : 'far' }} fa-heart {{ in_array($favorite->restaurant->id, $favoriteIds) ? 'text-danger' : '' }}"></i>
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
    </div>
</div>

<div id="cancelModal" class="modal">
    <div class="modal-content">
        <p>予約を取り消しますか？</p>
        <button id="confirmCancel">はい</button>
        <button id="cancelCancel">いいえ</button>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cancelButtons = document.querySelectorAll('.cancel-reservation');
        const modal = document.getElementById('cancelModal');
        const confirmCancel = document.getElementById('confirmCancel');
        const cancelCancel = document.getElementById('cancelCancel');
        let reservationId;

        cancelButtons.forEach(button => {
            button.addEventListener('click', function() {
                reservationId = this.getAttribute('data-id');
                modal.style.display = 'block';
            });
        });

        confirmCancel.addEventListener('click', function() {
            fetch(`/reservations/${reservationId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: reservationId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('予約がキャンセルされました');
                    location.reload();
                } else {
                    alert('キャンセルに失敗しました');
                }
            })
            .catch(error => alert('エラーが発生しました'));

            modal.style.display = 'none';
        });

        cancelCancel.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    });
</script>
@endsection

