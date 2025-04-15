@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/qrcode.css') }}">
@endsection

@section('content')
<div class="container">
    <h2>QRコード</h2>
    <div class="qr-code-container">
        <img src="{{ $qrCodeUrl }}" alt="QR Code">
    </div>
    <p>このQRコードを店舗側に提示してください。</p>
</div>
@endsection
