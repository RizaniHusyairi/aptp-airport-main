<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpidRegulation extends Model
{
    use HasFactory;

    protected $table = 'ppid_regulations';
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // TAMBAHKAN BARIS INI
        'published_date' => 'date',
    ];
}