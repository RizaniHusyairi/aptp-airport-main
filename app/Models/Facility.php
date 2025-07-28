<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'details',
        'image_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'details' => 'array', // Otomatis ubah JSON ke array dan sebaliknya
    ];
}
