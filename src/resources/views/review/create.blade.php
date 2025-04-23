@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review/create.css') }}">
@endsection

@section('content')
<h1 class="page-title">レビュー投稿</h1>

<form action="{{ route('review.store', ['reservation' => $reservation->id]) }}" method="POST" class="review-form">
    @csrf
    <div>
        <label class="form-label">評価（1〜5）:</label>
        <select name="rating" class="form-select" required>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
        @error('rating')
            <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="form-label">コメント:</label>
        <textarea name="comment" rows="5" cols="40" class="form-textarea">{{ old('comment') }}</textarea>
        @error('comment')
            <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="submit-button">送信</button>
</form>
@endsection
