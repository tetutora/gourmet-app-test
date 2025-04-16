<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        Reservation::createReservation($request);
        return redirect()->route('reservation.complete');
    }

    public function reservationComplete()
    {
        return view('reservation');
    }

    public function cancel($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->status_id = Constants::RESERVATION_STATUS_CANCELLED;
        $reservation->save();

        return response()->json(['success' => true]);
    }

    public function update(UpdateReservationRequest $request, $reservationId)
    {
        Reservation::updateReservation($reservationId, $request);
        return response()->json(['success' => true, 'message' => '予約を更新しました']);
    }
}
