<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Get the stations for the destination.
     */
    public function stations()
    {
        return $this->hasMany(Station::class);
    }
}
