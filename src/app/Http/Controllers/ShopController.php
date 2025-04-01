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
        $region_id = $request->input('region_id');
        $genre_id = $request->input('genre_id');
        $query = $request->input('query');

        $restaurants = Restaurant::with(['region', 'genre']);

        if ($region_id) {
            $restaurants->where('region_id', $region_id);
        }

        if ($genre_id) {
            $restaurants->where('genre_id', $genre_id);
        }

        if ($query) {
            $restaurants->where('name', 'like', "%$query%")
                        ->orWhereHas('region', function($q) use ($query) {
                            $q->where('name', 'like', "%$query%");
                        })
                        ->orWhereHas('genre', function($q) use ($query) {
                            $q->where('name', 'like', "%$query%");
                        });
        }

        $restaurants = $restaurants->get();
        $favorites = auth()->check() ? auth()->user()->favorites->pluck('restaurant_id')->toArray() : [];
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

        if (!Favorite::where('user_id', $user->id)->where('restaurant_id', $restaurantId)->exists()) {
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
