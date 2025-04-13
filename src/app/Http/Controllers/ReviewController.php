<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Reservation $reservation)
    {
        return view('review.create', compact('reservation'));
    }

    public function store(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $reservation->restaurant_id,
            'reservation_id' => $reservation->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('restaurants.detail', ['restaurant' => $reservation->restaurant_id])
                        ->with('success', 'レビューを投稿しました。');
    }
}
