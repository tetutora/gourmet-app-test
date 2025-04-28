@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
    <div class="thanks-container">
        <div class="thanks-box">
            <h3>会員登録ありがとうございます</h3>
            <p>下記リンクからメール認証お願いします</p>
            <a href="https://mailtrap.io/home" class="login-button">メール認証</a>
        </div>
    </div>
@endsection