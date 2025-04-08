@extends('layouts.app')

@section('content')
    <h1>店舗代表者ダッシュボード</h1>
    <a href="{{ route('representative.reservations') }}">予約情報を確認</a>
    <a href="{{ route('restaurants.create') }}">店舗情報を作成</a>
@endsection
