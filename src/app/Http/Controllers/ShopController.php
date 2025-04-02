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
        $restaurants = Restaurant::searchRestaurants($request->all());
        $favorites = [];
        if (auth()->check()) {
            $favorites = auth()->user()->favorites->pluck('restaurant_id')->toArray();
        }
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
        Favorite::addFavorite($user->id, $restaurantId);

        return response()->json(['success' => true]);
    }

    /**
     *お気に入り解除
     */
    public function removeFavorite($restaurantId)
    {
        $user = Auth::user();
        Favorite::removeFavorite($user->id, $restaurantId);

        return response()->json(['success' => true]);
    }
}
