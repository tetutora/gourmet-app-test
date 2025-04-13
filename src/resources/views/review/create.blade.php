@extends('layouts.app')

@section('content')
<h1>レビュー投稿</h1>

<form action="{{ route('review.store', $reservation) }}" method="POST">
    @csrf
    <label>評価（1〜5）:</label>
    <select name="rating" required>
        @for($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>

    <label>コメント（任意）:</label>
    <textarea name="comment" rows="5" cols="40"></textarea>

    <button type="submit">送信</button>
</form>
@endsection
