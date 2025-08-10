<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class slot extends Model
{
    protected $guarded = [];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'slot_user', 'slot_id', 'user_id')
                    ->withTimestamps();
    }
}
