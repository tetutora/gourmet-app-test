<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Reservation;
use App\Models\Review;


class ReviewController extends Controller
{
    public function create(Reservation $reservation)
    {
        return view('review.create', compact('reservation'));
    }

    public function store(ReviewRequest $request, Reservation $reservation)
    {
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
