<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'reservation_date',
        'reservation_time',
        'num_people',
    ];

    public static function getUpcomingReservationsForUser($userId)
    {
        $now = Carbon::now();

        return self::where('user_id', $userId)
            ->where('reservation_date', '>=', $now->toDateString())
            ->where(function ($query) use ($now) {
                $query->where('reservation_date', '>', $now->toDateString())
                    ->orWhere(function ($query) use ($now) {
                        $query->where('reservation_date', '=', $now->toDateString())
                            ->where('reservation_time', '>=', $now->toTimeString());
                    });
            })
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->get();
    }

    public static function createReservation($request)
    {
        self::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $request->restaurant_id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'num_people' => $request->num_people,
        ]);
    }

    public static function updateReservation($reservationId, $request)
    {
        $reservation = self::findOrFail($reservationId);
        $reservation->update($request->validated());
    }

    public static function cancelReservation($reservationId)
    {
        $reservation = self::findOrFail($reservationId);
        $reservation->delete();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
