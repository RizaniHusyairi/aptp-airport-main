<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkProgram extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi ke tugas-tugas dalam program kerja ini.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Accessor untuk menghitung persentase progres.
     * Dihitung berdasarkan tugas yang statusnya 'Diverifikasi'.
     *
     * @return float
     */
    public function getProgressPercentageAttribute(): float
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) {
            return 0;
        }

        // Hitung tugas yang statusnya 'Diverifikasi'
        $verifiedTasks = $this->tasks()->where('status', 'Diverifikasi')->count();

        return round(($verifiedTasks / $totalTasks) * 100);
    }
}
