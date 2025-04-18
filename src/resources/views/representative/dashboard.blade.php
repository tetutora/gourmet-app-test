@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative/dashboard.css') }}">
@endsection

@section('content')
    <h1>予約情報</h1>

    @foreach($reservationsByRestaurant as $restaurant => $reservations)
        <h2>{{ $restaurant }}</h2>

        <table>
            <thead>
                <tr>
                    <th>予約者</th>
                    <th>予約日</th>
                    <th>予約時間</th>
                    <th>来店人数</th>
                    <th>ステータス</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y年m月d日') }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</td>
                        <td>{{ $reservation->num_people }}名</td>
                        <td>{{ $reservation->status->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
@endsection
