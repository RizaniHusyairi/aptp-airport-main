<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Surat_event extends Model
{
    protected $guarded = [];
    protected $casts = ['meta' => 'array'];
    public $timestamps = true;
}
