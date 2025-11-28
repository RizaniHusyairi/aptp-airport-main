<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NataruEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi ke data penerbangan
    public function flights()
    {
        return $this->hasMany(NataruFlight::class);
    }

    // Relasi ke Event Pembanding (Self Join)
    public function compareEvent()
    {
        return $this->belongsTo(NataruEvent::class, 'compare_event_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            if (empty($event->public_token)) {
                $event->public_token = Str::random(32);
            }
        });
    }
}