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
    /**
     *飲食店一覧画面表示
     */
    public function index(Request $request)
    {
        $restaurants = Restaurant::searchRestaurants($request->all());
        $favorites = Favorite::favoritesForUser(Auth::id());
        $regions = Region::all();
        $genres = Genre::all();

        return view('index', compact('restaurants', 'favorites', 'regions', 'genres'));
    }

    /**
     *飲食店詳細画面表示
     */
    public function showDetail($id)
    {
        $restaurant = Restaurant::with(['region', 'genres', 'reviews.user'])->findOrFail($id);
        $averageRating = round($restaurant->reviews->avg('stars'), 1);

        return view('detail', compact('restaurant'));
    }

    /**
     *マイページ画面表示
     */
    public function showMypage()
    {
        $userId = Auth::id();
        $now = Carbon::now();

        $reservations = Reservation::getUpcomingReservationsForUser($userId);
        Reservation::updateStatusIfReservationPassed();

        $favorites = Favorite::favoritesForUser($userId);
        $favoriteIds = $favorites->pluck('restaurant_id')->toArray();

        $restaurants = Restaurant::with(['region', 'genres'])->get();

        return view('mypage', compact('reservations', 'favorites', 'favoriteIds', 'restaurants'));
    }

    /**
     *お気に入り登録
     */
    public function addFavorite($restaurantId)
    {
        Favorite::addFavorite(Auth::id(), $restaurantId);
        return response()->json(['success' => true]);
    }

    /**
     *お気に入り解除
     */
    public function removeFavorite($restaurantId)
    {
        Favorite::removeFavorite(Auth::id(), $restaurantId);
        return response()->json(['success' => true]);
    }
}
