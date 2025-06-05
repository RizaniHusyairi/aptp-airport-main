<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class slot extends Model
{
    protected $guarded = [];

    // use SoftDeletes;


    // protected $fillable = [
    //     'user_id',
    //     'aircraft_registration',
    //     'aircraft_type',
    //     'departure_schedule',
    //     'arrival_schedule',
    //     'origin_airport',
    //     'destination_airport',
    //     'flight_type',
    //     'flight_more',
    //     'documents',
    //     'status',
    //     'admin_comments',
    // ];

    // protected $casts = [
    //     'departure_schedule' => 'datetime',
    //     'arrival_schedule' => 'datetime',
    //     'status' => 'string',
    //     'flight_type' => 'string',
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
