<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryStatusLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Menonaktifkan updated_at
    const UPDATED_AT = null;

    /**
     * Relasi ke item inventaris.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Relasi ke user (staff) yang melakukan perubahan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}