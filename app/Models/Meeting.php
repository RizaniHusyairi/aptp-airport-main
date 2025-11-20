<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi ke User pembuat
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Peserta Hadir
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}