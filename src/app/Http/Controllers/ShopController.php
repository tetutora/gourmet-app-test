<?php

namespace App\Http\Controllers;

use App\Constants\RoleType;
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
        $favorites = Favorite::favoritesForUser(Auth::id());
        $regions = Region::all();
        $genres = Genre::all();

        return view('index', compact('restaurants', 'favorites', 'regions', 'genres'));
    }

    public function showDetail($id)
    {
        $restaurant = Restaurant::with(['region', 'genres', 'reviews.user'])->findOrFail($id);
        $averageRating = $restaurant->reviews->avg('rating');
        $averageRating = $averageRating ? round($averageRating, 2) : null;

        $reviewsPaginated = $restaurant->reviews()->paginate(25);

        return view('detail', compact('restaurant', 'averageRating', 'reviewsPaginated'));
    }

    public function showMypage()
    {
        $userId = Auth::id();
        $now = Carbon::now();

        Reservation::updateStatusIfReservationPassed();

        $upcomingReservations = Reservation::getUpcomingReservationsForUser($userId);
        $completedReservations = Reservation::getCompletedReservationsForUser($userId);

        $reservations = $upcomingReservations->merge($completedReservations);

        $favorites = Favorite::favoritesForUser($userId);
        $favoriteIds = $favorites->pluck('restaurant_id')->toArray();

        $restaurants = Restaurant::with(['region', 'genres'])->get();

        return view('mypage', compact('reservations', 'favorites', 'favoriteIds', 'restaurants'));
    }

    public function addFavorite($restaurantId)
    {
        Favorite::addFavorite(Auth::id(), $restaurantId);
        return response()->json(['success' => true]);
    }

    public function removeFavorite($restaurantId)
    {
        Favorite::removeFavorite(Auth::id(), $restaurantId);
        return response()->json(['success' => true]);
    }

    public function someFunction()
    {
        return view('your-view', [
            'RoleType' => RoleType::class,
        ]);
    }
}
