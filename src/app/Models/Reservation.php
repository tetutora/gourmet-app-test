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
        'status_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hasReview()
    {
        return $this->reviews()->exists();
    }

    public static function getUpcomingReservationsForUser($userId)
    {
        $now = Carbon::now();

        return self::where('user_id', $userId)
            ->where(function ($query) use ($now) {
                $query->where('reservation_date', '>', $now->toDateString())
                    ->orWhere(function ($query) use ($now) {
                        $query->where('reservation_date', '=', $now->toDateString())
                            ->where('reservation_time', '>=', $now->toTimeString());
                    });
            })
            ->whereDoesntHave('reviews')
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->get();
    }

    public static function getCompletedReservationsForUser($userId)
    {
        $now = Carbon::now();

        return self::where('user_id', $userId)
            ->where('status_id', 2)
            ->where(function ($query) use ($now) {
                $query->where('reservation_date', '<', $now->toDateString())
                    ->orWhere(function ($query) use ($now) {
                        $query->where('reservation_date', '=', $now->toDateString())
                            ->where('reservation_time', '<', $now->toTimeString());
                    });
            })
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->get();
    }

    public static function updateStatusIfReservationPassed()
    {
        $now = Carbon::now();

        $reservations = self::where('status_id', '!=', 2)
            ->where(function ($query) use ($now) {
                $query->whereRaw('CONCAT(reservation_date, " ", reservation_time) < ?', [$now]);
            })
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->update(['status_id' => 2]);
        }
    }

    public static function createReservation($request)
    {
        self::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $request->restaurant_id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'num_people' => $request->num_people,
            'status_id' => 1,
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
        $reservation->update([
            'status_id' => 3,
        ]);
    }
}