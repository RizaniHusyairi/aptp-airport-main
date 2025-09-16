<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ExtendAdvance extends Model
{
    use HasFactory;

    protected $table = 'extend_advances';
    protected $guarded = []; // Mengizinkan semua field diisi secara massal

    protected $casts = [
        'flight_date' => 'date',
    ];

    /**
     * Relasi ke pengguna (pengaju) yang membuat permohonan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
