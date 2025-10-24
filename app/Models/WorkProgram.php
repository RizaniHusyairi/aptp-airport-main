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
     *
     * @return float
     */
    public function getProgressPercentageAttribute(): float
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) {
            return 0; // Jika tidak ada tugas, progres 0%
        }

        $completedTasks = $this->tasks()->where('is_completed', true)->count();

        return round(($completedTasks / $totalTasks) * 100);
    }
}

