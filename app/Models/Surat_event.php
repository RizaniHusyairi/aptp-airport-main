<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surat_event extends Model
{
    protected $guarded = [];
    protected $casts = ['meta' => 'array'];
    public $timestamps = true;

     public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
