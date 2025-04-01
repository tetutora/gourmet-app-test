@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection

@section('content')
    <div class="thanks-container">
        <div class="thanks-box">
            <h3>会員登録ありがとうございます</h3>
            <a href="{{ route('login') }}" class="login-button">ログインする</a>
        </div>
    </div>
@endsection