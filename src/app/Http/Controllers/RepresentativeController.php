<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;

class RepresentativeController extends Controller
{
    public function representativeDashboard()
    {
        $user = auth()->user();

        $reservations = Reservation::where('restaurant_id', $user->restaurant_id)
        ->orderBy('reservation_date', 'asc')
        ->get();

        return view('representative.dashboard', ['reservations' => $reservations]);
    }

    public function create()
    {
        return view('representative.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'region_id' => 'required|exists:regions,id',
            'genre_id' => 'required|exists:genres,id',
        ]);

        // 新しい店舗情報を作成
        Restaurant::create([
            'name' => $request->name,
            'description' => $request->description,
            'region_id' => $request->region_id,
            'genre_id' => $request->genre_id,
            'user_id' => auth()->id(), // 店舗代表者のIDを紐づけ
        ]);

        return redirect()->route('representative.dashboard');
    }
}