@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="thank-you-container">
    <div class="thank-you-message">
        <p>ご予約ありがとうございます。</p>
        <!-- 戻るボタン -->
        <a href="{{ route('restaurants.detail', ['id' => $restaurant->id]) }}" class="back-button">戻る</a>
    </div>
</div>
@endsection
