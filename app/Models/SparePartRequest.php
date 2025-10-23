<?php

namespace App\Models;


use App\Models\User;
use App\Models\SparePart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SparePartRequest extends Model
{
    protected $guarded = [];

        /**
     * Relasi ke User (Staff) yang membuat permintaan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke SparePart yang diminta.
     */
    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class);
    }
}
