<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Region;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     *飲食店一覧画面表示
     */
    public function index(Request $request)
    {
        $region_id = $request->input('region_id');
        $genre_id = $request->input('genre_id');
        $query = $request->input('query');
        $restaurants = Restaurant::query();
        $restaurants = $restaurants->with(['region', 'genre']);

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

    /**
     *飲食店詳細画面表示
     */
    public function showDetail($id)
    {
        $restaurant = Restaurant::with(['region', 'genre'])->findOrFail($id);
        return view('detail', compact('restaurant'));
    }

    /**
     *マイページ画面表示
     */
    public function showMypage()
    {
        return view('mypage');
    }

    /**
     *お気に入り登録
     */
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

    /**
     *お気に入り解除
     */
    public function removeFavorite($restaurantId)
    {
        $user = Auth::user();

        Favorite::where('user_id', $user->id)->where('restaurant_id', $restaurantId)->delete();
        return response()->json(['success' => true]);
    }
}
