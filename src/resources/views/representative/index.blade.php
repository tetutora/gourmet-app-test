@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>あなたの店舗一覧</h1>

        @if($restaurants->isEmpty())
            <p>店舗が登録されていません。</p>
        @else
            <ul>
                @foreach ($restaurants as $restaurant)
                    <li>
                        <strong>{{ $restaurant->name }}</strong><br>
                        <a href="{{ route('restaurants.edit', ['restaurant' => $restaurant->id]) }}">編集</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
