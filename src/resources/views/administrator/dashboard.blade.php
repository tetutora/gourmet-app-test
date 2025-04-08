@extends('layouts.app')

@section('content')
    <h1>管理者ダッシュボード</h1>
    <a href="{{ route('administrator.users.create') }}">店舗代表者を作成</a>
    <div>
        <h2>店舗代表者一覧</h2>
        <!-- 店舗代表者のリスト表示など -->
    </div>
@endsection
