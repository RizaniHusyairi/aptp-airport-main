<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Cast status sebagai string (karena sudah enum)
    protected $casts = [
        // Hapus 'is_completed' jika masih ada
    ];

    /**
     * Relasi ke program kerja induk.
     */
    public function workProgram(): BelongsTo
    {
        return $this->belongsTo(WorkProgram::class);
    }

    /**
     * Relasi ke user (Kanit) yang melakukan verifikasi.
     */
    public function verifier(): BelongsTo
    {
        // Relasi ke User model, menggunakan foreign key 'verifier_id'
        return $this->belongsTo(User::class, 'verifier_id');
    }
}

