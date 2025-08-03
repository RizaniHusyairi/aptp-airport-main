<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Tourism extends Model
{
    protected $guarded = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'category',
        'cover_image',
        'gallery',
        'short_desc',
        'description',
        'address',
        'gmaps_url',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gallery' => 'array', // Ini penting! Laravel akan otomatis mengubah array ke JSON saat menyimpan, dan sebaliknya.
    ];
}
