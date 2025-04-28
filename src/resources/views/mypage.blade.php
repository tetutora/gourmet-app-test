@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="mypage-content">
        <div class="mypage-left">
            <p class="left-title">予約状況</p>
            <div class="reservations">
                <h3>予約済み</h3>
                @foreach ($reservations as $reservation)
                    @if ($reservation->status_id === \App\Constants\Constants::RESERVATION_STATUS_BOOKED)
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
                                       data-id="{{ $reservation->id }}" value="{{ $reservation->num_people }}">
                            </div>

                            <button class="update-reservation" data-id="{{ $reservation->id }}">更新</button>

                            <a href="{{ route('reservations.qrcode', ['reservation' => $reservation->id]) }}"
                               class="btn-qr-code">
                                QRコードを表示
                            </a>
                            @if ($reservation->payment_method === 'card')
                                <a href="{{ route('payment.form') }}" class="btn-payment">カードお支払い</a>
                            @endif
                        </div>
                    @endif
                @endforeach

                <h3>来店済み</h3>
                @foreach ($reservations as $reservation)
                    @if ($reservation->status_id === \App\Constants\Constants::RESERVATION_STATUS_COMPLETED)
                        <div class="reservation-item">
                            <div class="reservation-header">
                                <!-- 来店済みの予約ではキャンセルボタンを表示しない -->
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
                                       data-id="{{ $reservation->id }}" value="{{ $reservation->num_people }}">
                            </div>

                            @if (!$reservation->hasReview())
                                <a href="{{ route('review.create', ['reservation' => $reservation->id]) }}" class="btn-review">
                                    <button class="btn btn-primary">レビューを投稿</button>
                                </a>
                            @else
                                <p>レビュー投稿済み</p>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- お気に入り店舗 -->
        <div class="mypage-right">
            <p class="right-title">お気に入り店舗</p>
            <div class="row">
                @foreach ($favorites as $favorite)
                    <div class="col favorite-card" data-restaurant-id="{{ $favorite->restaurant->id }}">
                        <div class="card shadow-custom">
                            <img src="{{ Str::startsWith($favorite->restaurant->image_url, ['http://', 'https://']) ? $favorite->restaurant->image_url : asset('storage/' . $favorite->restaurant->image_url) }}" class="card-img-top" alt="{{ $favorite->restaurant->name }}">
                            <div class="card-body">
                                <p class="card-title">{{ $favorite->restaurant->name }}</p>
                                <p class="card-text">
                                    <span class="badge bg-secondary">#{{ $favorite->restaurant->region->name }}</span>
                                    <span class="badge bg-secondary">
                                        @foreach($favorite->restaurant->genres as $genre)
                                            #{{ $genre->name }}
                                        @endforeach
                                    </span>
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
    // 予約更新
    document.querySelectorAll('.update-reservation').forEach(button => {
        button.addEventListener('click', function() {
            const reservationId = this.getAttribute('data-id');
            const dateField = document.querySelector(`.reservation-date[data-id='${reservationId}']`);
            const timeField = document.querySelector(`.reservation-time[data-id='${reservationId}']`);
            const numField = document.querySelector(`.reservation-num[data-id='${reservationId}']`);

            const dateInput = dateField.value;
            const timeInput = timeField.value;
            const numPeopleInput = numField.value;

            [dateField, timeField, numField].forEach(field => {
                const error = field.nextElementSibling;
                if (error && error.classList.contains('error-message')) {
                    error.remove();
                }
            });

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
            .then(async response => {
                const data = await response.json();
                if (response.ok && data.success) {
                    alert('予約を更新しました');
                } else if (data.errors) {
                    const errorMap = {
                        reservation_date: dateField,
                        reservation_time: timeField,
                        num_people: numField
                    };
                    for (let field in data.errors) {
                        const input = errorMap[field];
                        const error = document.createElement('p');
                        error.classList.add('error-message');
                        error.textContent = data.errors[field][0];
                        input.parentNode.insertBefore(error, input.nextSibling);
                    }
                } else {
                    alert('更新に失敗しました');
                }
            })
            .catch(error => {
                console.error(error);
                alert('エラーが発生しました');
            });
        });
    });

    // お気に入り削除
    document.querySelectorAll('.btn-favorite').forEach(button => {
        button.addEventListener('click', function() {
            const restaurantId = this.getAttribute('data-restaurant-id');
            const icon = this.querySelector('.favorite-icon');
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
                    card.remove();
                } else {
                    alert('処理に失敗しました');
                }
            })
            .catch(() => alert('エラーが発生しました'));
        });
    });

    // 予約キャンセル
    document.querySelectorAll('.cancel-reservation').forEach(button => {
        button.addEventListener('click', function() {
            const reservationId = this.getAttribute('data-id');

            if (!confirm('本当にこの予約をキャンセルしますか？')) return;

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
                    location.reload();
                } else {
                    alert('キャンセルに失敗しました');
                }
            })
            .catch(() => alert('エラーが発生しました'));
        });
    });
});
</script>
@endsection
