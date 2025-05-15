<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'subject',
        'message',
        'status',
    ];
}
