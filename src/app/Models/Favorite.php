<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public static function favoritesForUser($userId)
    {
        return self::where('user_id', $userId)
            ->with('restaurant')
            ->get();
    }

    public static function addFavorite($userId, $restaurantId)
    {
        $exists = self::where('user_id', $userId)->where('restaurant_id', $restaurantId)->exists();

        if (!$exists) {
            self::create([
                'user_id' => $userId,
                'restaurant_id' => $restaurantId,
            ]);
        }
    }

    public static function removeFavorite($userId, $restaurantId)
    {
        self::where('user_id', $userId)->where('restaurant_id', $restaurantId)->delete();
    }
}
