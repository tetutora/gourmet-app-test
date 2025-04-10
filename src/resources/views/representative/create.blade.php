@extends('layouts.app')

@section('content')
    <h1>店舗情報作成</h1>

    <form action="{{ route('restaurants.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">店舗名</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div>
            <label for="description">店舗説明</label>
            <textarea id="description" name="description"></textarea>
        </div>

        <div>
            <label for="region_id">地域</label>
            <select id="region_id" name="region_id" required>
                <!-- 地域の選択肢を表示 -->
                @foreach ($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="genre_id">ジャンル</label>
            <select id="genre_id" name="genre_id" required>
                <!-- ジャンルの選択肢を表示 -->
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit">店舗作成</button>
        </div>
    </form>
@endsection
