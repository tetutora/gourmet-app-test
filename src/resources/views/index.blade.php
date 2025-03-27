@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container"> {{-- container-fluidをcontainerに変更 --}}
    <div class="row">
        @foreach($restaurants as $restaurant)
        <div class="col-12 col-md-3 mb-4">
            <div class="card h-100 shadow-custom">
                <img src="{{ $restaurant->image_url }}" class="card-img-top" alt="{{ $restaurant->name }}">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $restaurant->name }}</h5>
                    <p class="card-text">
                        <span class="badge bg-secondary">#{{ $restaurant->region }}</span>
                        <span class="badge bg-secondary">#{{ $restaurant->genre }}</span>
                    </p>
                    <a href="{{ route('restaurants.detail', $restaurant->id) }}" class="btn btn-primary btn-sm btn-detail">詳しく見る</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection