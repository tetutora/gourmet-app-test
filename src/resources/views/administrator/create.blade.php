@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/create.css') }}">
@endsection

@section('content')
<div class="container">
    <h2>店舗代表者の作成</h2>
    <form action="{{ route('administrator.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">名前</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
            @error('name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
            @error('email')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">パスワード（確認）</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            @error('password_confirmation')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">作成</button>
    </form>
</div>
@endsection
