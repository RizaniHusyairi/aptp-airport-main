<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OjtStudent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'birth_date' => 'date',
        'start_date' => 'date',
        'end_date'   => 'date',
        'supervisors' => 'array', // Otomatis jadi array PHP
        'work_units'  => 'array', // Otomatis jadi array PHP
    ];
}
