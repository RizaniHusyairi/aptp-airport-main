<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLogbook extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * === PERBAIKAN DI SINI ===
     * Beritahu Laravel untuk otomatis mengubah 'documentation'
     * dari array PHP -> JSON (saat menyimpan)
     * dan dari JSON -> array PHP (saat mengambil)
     */
    protected $casts = [
        'log_date' => 'date',
        'documentation' => 'array', // Otomatis konversi JSON ke array
    ];

    /**
     * Relasi ke item inventaris.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Relasi ke user (teknisi) yang membuat log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

