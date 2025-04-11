<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'region_id', 'genre_id', 'description', 'user_id', 'image_url'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_restaurant');
    }

    public static function searchRestaurants($filters)
    {
        $query = self::with(['region', 'genres']);

        if (!empty($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }

        if (!empty($filters['genre_id'])) {
            $query->whereHas('genres', function ($q) use ($filters) {
                $q->where('genres.id', $filters['genre_id']);
            });
        }

        if (!empty($filters['query'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['query']}%")
                ->orWhereHas('region', function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters['query']}%");
                })
                ->orWhereHas('genres', function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters['query']}%");
                });
            });
        }

        return $query->get();
    }

    public function attachGenres(array $genreIds, ?string $newGenres = null): void
    {
        if (!empty($newGenres)) {
            $newGenresArray = array_map('trim', explode(',', $newGenres));
            foreach ($newGenresArray as $genreName) {
                $genre = Genre::firstOrCreate(['name' => $genreName]);
                $genreIds[] = $genre->id;
            }
        }

        $this->genres()->sync($genreIds);
    }

    public static function uploadImage(?\Illuminate\Http\UploadedFile $image): ?string
    {
        return $image ? $image->store('restaurants', 'public') : null;
    }
}
