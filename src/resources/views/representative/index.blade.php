@extends('layouts.app')

@foreach($restaurants as $restaurant)
    <div>{{ $restaurant->name }}</div>
@endforeach
