@extends('layouts.app')

@section('content')
    <h1>店舗情報作成</h1>

    <form action="{{ route('restaurants.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">店舗名</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div>
            <label for="description">店舗説明</label>
            <textarea name="description" id="description"></textarea>
        </div>

        <div>
            <label for="region_id">地域</label>
            <select name="region_id" id="region_id" required>
                <option value="">選択してください</option>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="genre_id">ジャンル</label>
            <select name="genre_id" id="genre_id" required>
                <option value="">選択してください</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit">店舗情報を作成</button>
        </div>
    </form>
@endsection
