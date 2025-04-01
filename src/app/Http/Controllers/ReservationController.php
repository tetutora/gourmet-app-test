<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use App\Models\restaurant;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        Reservation::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $request->restaurant_id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'num_people' => $request->num_people,
        ]);

        return redirect()->route('reservation.complete');
    }

    public function reservationComplete()
    {
        $restaurant = Restaurant::first();

        return view('reservation', compact('restaurant'));
    }
}
