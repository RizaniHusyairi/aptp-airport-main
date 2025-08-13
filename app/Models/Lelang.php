<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Lelang extends Model
{
    protected $guarded = [];
   

    public function users()
    {
        return $this->belongsToMany(User::class, 'lelang_user')
                   ->withTimestamps();
    }
}
