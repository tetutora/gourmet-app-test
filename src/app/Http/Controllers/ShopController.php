<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     *飲食店一覧画面表示
     */
    public function index()
    {
        $restaurants = Restaurant::all();
        $favorites = auth()->check() ? auth()->user()->favorites->pluck('restaurant_id')->toArray() : [];

        return view('index', compact('restaurants', 'favorites'));
    }

    /**
     *飲食店詳細画面表示
     */
    public function showDetail()
    {
        return view('detail');
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
