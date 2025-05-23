<?php

namespace App\Models;

use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'restaurant_id', 'reservation_id', 'rating', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function getRatingsAttribute()
    {
        return $this->attributes['rating'];
    }
}
