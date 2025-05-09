<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Lelang extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'name',
        'lelang_type',
        'description',
        'documents',
        'additional_documents',
        'submission_status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'lelang_user')
                   ->withTimestamps();
    }
}
