@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/notify.css') }}">
@endsection

@section('content')
<div class="container">
    <h1 class="title">利用者へのお知らせ送信</h1>
    <form action="{{ route('administrator.notify.send') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="message">お知らせ内容</label>
            <textarea name="message" id="message" rows="6" class="form-control" required>{{ old('message') }}</textarea>
            @error('message')
                <p class="text-danger">{{ $message }}</p>
            @enderror
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary mt-2">送信</button>
    </form>
</div>
@endsection