<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NataruFlight extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'flight_date' => 'date',
        'load_factor' => 'decimal:2',
    ];

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(NataruEvent::class, 'nataru_event_id');
    }

    // Relasi ke User (jika diinput oleh staff login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nataruEvent()
    {
        // Asumsi nama foreign key di database adalah 'nataru_event_id'
        return $this->belongsTo(NataruEvent::class, 'nataru_event_id');
    }
}