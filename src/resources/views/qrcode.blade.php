@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/qrcode.css') }}">
@endsection

@section('content')
<div class="container">
    <h2 class="page-title">QRコード</h2>
    <div class="qr-code-container">
        <img src="{{ $qrCodeUrl }}" alt="QR Code" class="qr-code-image">
    </div>
    <p class="description-text">このQRコードを店舗側に提示してください。</p>
</div>
@endsection