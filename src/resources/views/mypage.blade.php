@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="mypage-content">
        <!-- 予約状況 -->
        <div class="mypage-left">
            <p class="left-title">予約状況</p>
            <div class="reservations">
                @foreach ($reservations as $reservation)
                    <div class="reservation-item">
                        <div class="reservation-header">
                            <p><i class="fas fa-clock"></i> 予約{{ $loop->iteration }}</p>
                            <button class="cancel-reservation" data-id="{{ $reservation->id }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p><strong>Shop</strong> {{ $reservation->restaurant->name }}</p>

                        <div class="input-group">
                            <label for="date-{{ $reservation->id }}">Date</label>
                            <input type="date" id="date-{{ $reservation->id }}" class="reservation-date"
                                data-id="{{ $reservation->id }}" value="{{ $reservation->reservation_date }}">
                        </div>

                        <div class="input-group">
                            <label for="time-{{ $reservation->id }}">Time</label>
                            <input type="time" id="time-{{ $reservation->id }}" class="reservation-time"
                                data-id="{{ $reservation->id }}" value="{{ $reservation->reservation_time }}">
                        </div>

                        <div class="input-group">
                            <label for="num-{{ $reservation->id }}">Number</label>
                            <input type="number" id="num-{{ $reservation->id }}" class="reservation-num"
                                data-id="{{ $reservation->id }}" value="{{ $reservation->num_people }}" min="1">
                        </div>
                        <button class="update-reservation" data-id="{{ $reservation->id }}">更新</button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- お気に入り店舗 -->
        <div class="mypage-right">
            <h3>お気に入り店舗</h3>
            <div class="row">
                @foreach ($favorites as $favorite)
                    <div class="col favorite-card" data-restaurant-id="{{ $favorite->restaurant->id }}">
                        <div class="card shadow-custom">
                            <img src="{{ $favorite->restaurant->image_url }}" class="card-img-top" alt="{{ $favorite->restaurant->name }}">
                            <div class="card-body">
                                <p class="card-title">{{ $favorite->restaurant->name }}</p>
                                <p class="card-text">
                                    <span class="badge bg-secondary">#{{ $favorite->restaurant->region->name }}</span>
                                    <span class="badge bg-secondary">#{{ $favorite->restaurant->genre->name }}</span>
                                </p>
                                <div class="btn-group">
                                    <a href="{{ route('restaurants.detail', $favorite->restaurant->id) }}" class="btn-detail">詳細を見る</a>
                                    @auth
                                    <button class="btn-favorite" data-restaurant-id="{{ $favorite->restaurant->id }}">
                                        <i class="fas fa-heart favorite-icon text-danger"></i>
                                    </button>
                                    @else
                                    <button class="btn-favorite" disabled>
                                        <i class="far fa-heart"></i>
                                    </button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.update-reservation').forEach(button => {
        button.addEventListener('click', function() {
            const reservationId = this.getAttribute('data-id');
            const dateInput = document.querySelector(`.reservation-date[data-id='${reservationId}']`).value;
            const timeInput = document.querySelector(`.reservation-time[data-id='${reservationId}']`).value;
            const numPeopleInput = document.querySelector(`.reservation-num[data-id='${reservationId}']`).value;

            fetch(`/reservations/${reservationId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    reservation_date: dateInput,
                    reservation_time: timeInput,
                    num_people: numPeopleInput
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('予約を更新しました');
                } else {
                    alert('更新に失敗しました');
                }
            })
            .catch(error => alert('エラーが発生しました'));
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-favorite').forEach(button => {
        button.addEventListener('click', function() {
            const restaurantId = this.getAttribute('data-restaurant-id');
            const icon = this.querySelector('.favorite-icon');
            const isFavorite = icon.classList.contains('fas');
            const card = this.closest('.favorite-card');

            fetch(`/favorites/remove/${restaurantId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (isFavorite) {
                        icon.classList.remove('fas', 'text-danger');
                        icon.classList.add('far');

                        if (card) {
                            card.remove();
                        }
                    } else {
                        icon.classList.remove('far');
                        icon.classList.add('fas', 'text-danger');
                    }
                } else {
                    alert('処理に失敗しました');
                }
            })
            .catch(error => alert('エラーが発生しました'));
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cancel-reservation').forEach(button => {
        button.addEventListener('click', function() {
            const reservationId = this.getAttribute('data-id');

            if (!confirm('本当にこの予約をキャンセルしますか？')) {
                return;
            }

            fetch(`/reservations/${reservationId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('予約をキャンセルしました');
                    this.closest('.reservation-item').remove();
                } else {
                    alert('キャンセルに失敗しました');
                }
            })
            .catch(error => alert('エラーが発生しました'));
        });
    });
});


</script>
@endsection
