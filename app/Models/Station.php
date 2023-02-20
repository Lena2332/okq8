<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Get the experiences for the station.
     */
    public function experience()
    {
        return $this->hasOne(Experience::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
