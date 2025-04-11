<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurantRequest;
use App\Models\Genre;
use App\Models\Region;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepresentativeController extends Controller
{
    /**
     * 店舗代表者ダッシュボード
     */
    public function representativeDashboard()
    {
        $user = auth()->user();

        $reservations = Reservation::where('restaurant_id', $user->restaurant_id)
            ->orderBy('reservation_date', 'asc')
            ->get();

        return view('representative.dashboard', ['reservations' => $reservations]);
    }

    /**
     * 店舗情報作成ページ
     */
    public function create()
    {
        $regions = Region::all();
        $genres = Genre::all();

        return view('representative.create', compact('regions', 'genres'));
    }

    /**
     * 店舗情報登録処理
     */
    public function store(StoreRestaurantRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request) {
            $imagePath = Restaurant::uploadImage($request->file('image_url'));

            $restaurant = Restaurant::create([
                'name' => $request->name,
                'description' => $request->description,
                'region_id' => $request->region_id,
                'user_id' => auth()->id(),
                'image_url' => $imagePath,
            ]);

            $restaurant->attachGenres(
                $request->input('genre_ids', []),
                $request->input('new_genres')
            );
        });

        return redirect()->route('index');
    }
}