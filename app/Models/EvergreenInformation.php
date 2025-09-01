<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvergreenInformation extends Model
{
    use HasFactory;
    protected $table = 'evergreen_information';
    protected $guarded = [];
    protected $casts = [
        'published_date' => 'date',
    ];
}