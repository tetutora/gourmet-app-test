@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative/create.css') }}">
@endsection

@section('content')
    <h1 class="page-title">店舗情報編集</h1>

    <!-- 新しい<div>タグを作成して、その中に両方のフォームを配置 -->
    <div class="form-wrapper">
        <form class="form-container" action="{{ route('restaurants.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label class="label" for="name">店舗名</label>
                <input class="input-field" type="text" id="name" name="name" value="{{ old('name', $restaurant->name) }}">
                @error('name')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label" for="description">店舗説明</label>
                <textarea class="custom-textarea" id="description" name="description">{{ old('description', $restaurant->description) }}</textarea>
                @error('description')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label" for="region_id">地域</label>
                <select class="select-field" id="region_id" name="region_id">
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}" {{ old('region_id', $restaurant->region_id) == $region->id ? 'selected' : '' }}>
                            {{ $region->name }}
                        </option>
                    @endforeach
                </select>
                @error('region_id')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label" for="genre_ids">ジャンル（複数選択可）</label>
                <select class="select-field" id="genre_ids" name="genre_ids[]" multiple size="4">
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}"
                            {{ in_array($genre->id, old('genre_ids', $restaurant->genres->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
                @error('genre_ids')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label" for="new_genres">新しいジャンル（カンマ区切り）</label>
                <input class="input-field" type="text" id="new_genres" name="new_genres" value="{{ old('new_genres') }}">
                @error('new_genres')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label" for="image_url">店舗画像</label>
                <input class="file-input" type="file" id="image_url" name="image_url" accept="image/*">
                @error('image_url')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <div id="preview-container" style="margin-top: 15px;">
                    <img id="image-preview" src="{{ $restaurant->image_url ? asset('storage/' . $restaurant->image_url) : '#' }}" alt="画像プレビュー" style="{{ $restaurant->image_url ? 'display:block' : 'display:none' }};">
                </div>
            </div>

            <div>
                <button class="submit-button" type="submit">店舗更新</button>
            </div>
        </form>
        <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
            @csrf
            @method('DELETE')
            <button type="submit" class="submit-button delete-button">店舗削除</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById('image_url');
            const previewImg = document.getElementById('image-preview');

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImg.src = '#';
                    previewImg.style.display = 'none';
                }
            });
        });
    </script>
@endsection
