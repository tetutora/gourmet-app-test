<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'region_id', 'genre_id', 'description', 'image_url'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function genre()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_restaurant');
    }

    public static function searchRestaurants($filters)
    {
        $query = self::with(['region', 'genre']);

        if (!empty($filters['region_id'])){
            $query->where('region_id', $filters['region_id']);
        }
        if (!empty($filters['genre_id'])){
            $query->where('genre_id', $filters['genre_id']);
        }
        if (!empty($filters['query'])){
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['query']}%")
                    ->orWhereHas('region', function ($q) use ($filters) {
                        $q->where('name', 'like', "%{$filters['query']}%");
                    })
                    ->orWhereHas('genre', function ($q) use ($filters) {
                        $q->where('name', 'like', "%{$filters['query']}%");
                    });
            });
        }
        return $query->get();
    }
}
