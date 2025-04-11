@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative/create.css') }}">
@endsection

@section('content')
    <h1>店舗情報作成</h1>

    <form action="{{ route('restaurants.store') }}" method="POST" enctype="multipart/form-data">
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
                @foreach ($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="genre_ids">ジャンル（複数選択可）</label>
            <select id="genre_ids" name="genre_ids[]" multiple size="5">
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="new_genres">新しいジャンル（カンマ区切り）</label>
            <input type="text" id="new_genres" name="new_genres" placeholder="例: カフェ,ラーメン">
        </div>

        <div>
            <label for="image_url">店舗画像</label>
            <input type="file" id="image_url" name="image_url" accept="image/*">
            <div id="preview-container" style="margin-top: 10px;">
                <img id="image-preview" src="#" alt="画像プレビュー" />
            </div>
        </div>

        <div>
            <button type="submit">店舗作成</button>
        </div>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectElement = document.getElementById('genre_ids');
            const displayDiv = document.getElementById('selected-genres');

            // 変更時に選択内容を表示する
            selectElement.addEventListener('change', function() {
                const selectedOptions = Array.from(selectElement.selectedOptions);
                const selectedGenres = selectedOptions.map(option => {
                    // 選択されたジャンルにチェックマークを付ける
                    return `<span class="selected-option selected">${option.text}</span>`;
                }).join(', ');
            });
        });

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
