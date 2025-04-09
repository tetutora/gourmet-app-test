@extends('layouts.app')

@section('content')
    <h1>予約情報</h1>
    <table>
        <thead>
            <tr>
                <th>予約ID</th>
                <th>予約者</th>
                <th>予約日</th>
                <th>ステータス</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->user->name }}</td>
                    <td>{{ $reservation->reservation_date }}</td>
                    <td>{{ $reservation->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
