<?php

namespace App\Models;


use App\Models\persuratan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class SuratVerification extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'persuratan_id',
        'user_id',
        'status',
        'order',
        'comments',
    ];

    /**
     * Relasi ke surat utama.
     */
    public function letter(): BelongsTo
    {
        return $this->belongsTo(persuratan::class);
    }

    /**
     * Relasi ke pengguna (pejabat) yang memverifikasi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
