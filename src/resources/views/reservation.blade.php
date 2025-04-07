@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
@endsection

@section('content')
<div class="thank-you-container">
    <div class="thank-you-message">
        <p>ご予約ありがとうございます。</p>
        <!-- 戻るボタン -->
        <button onclick="window.history.back()" class="back-button">戻る</button>
    </div>
</div>
@endsection
