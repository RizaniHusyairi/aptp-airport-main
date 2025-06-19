<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
     use HasFactory;
    protected $guarded = ['id']; // Memperbolehkan semua field diisi

    protected $casts = [
        'requirements' => 'array',
        'steps' => 'array',
        'pricing_info' => 'array',
        'has_pricing' => 'boolean',
        'is_active' => 'boolean',
    ];
}
