<?php

namespace App\Models;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['name'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
