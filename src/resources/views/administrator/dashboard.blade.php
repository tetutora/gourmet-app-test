@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>店舗代表者一覧</h1>

    <a href="{{ route('administrator.create') }}" class="btn btn-primary">新規作成</a>

    @if ($representatives->isEmpty())
        <p>店舗代表者が登録されていません。</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>作成日</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($representatives as $representative)
                    <tr>
                        <td>{{ $representative->name }}</td>
                        <td>{{ $representative->email }}</td>
                        <td>{{ $representative->created_at->format('Y/m/d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
