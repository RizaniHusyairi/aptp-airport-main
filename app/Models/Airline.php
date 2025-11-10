<?php

namespace App\Models;


use Spatie\MediaLibrary\HasMedia;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Airline extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $guarded = [];

    public function planes()
    {
        return $this->hasMany(Plane::class);
    }

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }
}
