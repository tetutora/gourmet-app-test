<?php

namespace App\Models;

use App\Constants\Constants;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Status;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'reservation_date',
        'reservation_time',
        'num_people',
        'status_id',
        'payment_method',
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
            ->where('status_id', Constants::RESERVATION_STATUS_COMPLETED)
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

        $reservations = self::where('status_id', '!=', Constants::RESERVATION_STATUS_COMPLETED)
            ->where(function ($query) use ($now) {
                $query->whereRaw('CONCAT(reservation_date, " ", reservation_time) < ?', [$now]);
            })
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->update(['status_id' => Constants::RESERVATION_STATUS_COMPLETED]);
        }
    }

    public static function createReservation($request)
    {
        return self::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $request->restaurant_id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'num_people' => $request->num_people,
            'payment_method' => $request->payment_method,
            'status_id' => Constants::RESERVATION_STATUS_BOOKED,
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
            'status_id' => Constants::RESERVATION_STATUS_CANCELLED,
        ]);
    }

    public static function getGroupedReservationsForUserRestaurants($userId)
    {
        $restaurantIds = Restaurant::where('user_id', $userId)->pluck('id');

        return self::whereIn('restaurant_id', $restaurantIds)
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->get()
            ->groupBy(function ($reservation) {
                return $reservation->restaurant->name;
            });
    }
}