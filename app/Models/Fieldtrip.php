<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Fieldtrip extends Model
{
    protected $guarded = [];

    protected $casts = [
        'documents' => 'array', // Otomatis konversi JSON <-> Array
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
