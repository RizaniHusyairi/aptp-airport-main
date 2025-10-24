<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'input_date' => 'date',
    ];

    /**
     * Relasi ke riwayat perubahan status.
     */
    public function statusLogs(): HasMany
    {
        // Urutkan berdasarkan yang terbaru
        return $this->hasMany(InventoryStatusLog::class)->latest();
    }
}