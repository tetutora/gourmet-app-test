<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use App\Models\restaurant;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Requests\ReservationRequest;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
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

    public function cancel($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $reservation->delete();

        return response()->json(['success' => true]);
    }

    public function update(UpdateReservationRequest $request, $reservationId)
    {
        // dd($request->all());
        $reservation = Reservation::findOrFail($reservationId);

        $reservation->update($request->validated());

        return response()->json(['success' => true, 'message' => '予約を更新しました']);
    }
}
