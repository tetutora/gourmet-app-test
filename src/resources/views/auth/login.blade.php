@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login">
    <div class="login-title">
        <p>Login</p>
    </div>
    <form action="/login" method="post">
        @csrf
        <div class="login-form">
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    <span>Email</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    <span>Password</span>
                </label>
                <input type="password" id="password" name="password">
            </div>
            <div class="login-button">
                <button type="submit">ログイン</button>
            </div>
        </div>
    </form>
</div>
@endsection
