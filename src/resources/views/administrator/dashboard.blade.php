@extends('layouts.app')

@section('content')
<div class="container">
    <h1>管理者ダッシュボード</h1>

    <a href="{{ route('administrator.create') }}" class="btn btn-primary">店舗代表者の作成</a>
</div>
@endsection
