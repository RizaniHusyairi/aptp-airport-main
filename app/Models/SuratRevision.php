<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratRevision extends Model
{
    protected $guarded = [];

    /**
     * Mendefinisikan relasi "belongsTo" ke model User.
     * Setiap revisi dimiliki oleh satu pengguna.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi "belongsTo" ke model persuratan.
     * Setiap revisi terkait dengan satu surat.
     */
    public function letter(): BelongsTo
    {
        return $this->belongsTo(persuratan::class);
    }
}
