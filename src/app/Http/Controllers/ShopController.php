<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Region;
use App\Models\Reservation;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $restaurants = Restaurant::searchRestaurants($request->all());
        $favorites = [];
        if (auth()->check()) {
            $favorites = auth()->user()->favorites->pluck('restaurant_id')->toArray();
        }
        $regions = Region::all();
        $genres = Genre::all();

        return view('index', compact('restaurants', 'favorites', 'regions', 'genres'));
    }

    public function showDetail($id)
    {
        $restaurant = Restaurant::with(['region', 'genre'])->findOrFail($id);
        return view('detail', compact('restaurant'));
    }

    public function showMypage()
    {
        $now = Carbon::now();

        $restaurants = Restaurant::with(['region', 'genre'])->get();

        $reservations = Reservation::where('reservation_date', '>=', $now->toDateString())
                                ->where(function ($query) use ($now) {
                                    $query->where('reservation_date', '>', $now->toDateString())
                                        ->orWhere(function ($query) use ($now) {
                                            $query->where('reservation_date', '=', $now->toDateString())
                                                    ->where('reservation_time', '>=', $now->toTimeString());
                                        });
                                })
                                ->orderBy('reservation_date', 'asc')
                                ->orderBy('reservation_time', 'asc')
                                ->get();

        $favorites = Favorite::where('user_id', Auth::id())->with('restaurant')->get();
        $favoriteIds = $favorites->pluck('restaurant_id')->toArray();

        return view('mypage', compact('reservations', 'favorites', 'favoriteIds', 'restaurants'));
    }

    public function addFavorite($restaurantId)
    {
        $user = Auth::user();

        if (!Favorite::where('user_id', $user->id)->where('restaurant_id', $restaurantId)->exists())
        {
            Favorite::create([
                'user_id' => $user->id,
                'restaurant_id' => $restaurantId,
            ]);
        }
        return response()->json(['success' => true]);
    }

    public function removeFavorite($restaurantId)
    {
        $user = Auth::user();

        Favorite::where('user_id', $user->id)->where('restaurant_id', $restaurantId)->delete();
        return response()->json(['success' => true]);
    }
}
