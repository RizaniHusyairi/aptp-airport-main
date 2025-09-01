<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImmediateInformation extends Model
{
    use HasFactory;
    protected $table = 'immediate_information';
    protected $guarded = []; // Izinkan semua field diisi
}
