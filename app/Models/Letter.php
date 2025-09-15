<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $guarded = [];
    protected $casts = [
        'issue_date' => 'date', // Tambahkan baris ini
    ];
}
