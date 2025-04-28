<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function create(Reservation $reservation)
    {
        return view('review.create', compact('reservation'));
    }

    public function store(ReviewRequest $request, Reservation $reservation)
    {
        $validated = $request->validated();

        $reservation->load('restaurant');

        Review::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $reservation->restaurant->id,
            'reservation_id' => $reservation->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('mypage')->with('success', 'レビューを投稿しました。');
    }
}
