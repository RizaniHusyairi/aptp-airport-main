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

    // Fungsi otomatis generate token saat event dibuat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            if (empty($event->public_token)) {
                $event->public_token = Str::random(32); // Token acak 32 karakter
            }
        });
    }
}