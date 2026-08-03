<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Letter extends Model
{
    protected $guarded = [];

    protected $casts = [
        'issue_date' => 'date',
    ];

    /**
     * Apakah berkas PDF-nya benar-benar ada di disk.
     *
     * Kolom `file_path` bisa menunjuk ke berkas yang tidak pernah ada —
     * misalnya baris hasil seeder contoh, atau berkas yang terhapus manual
     * dari server. Tanpa pemeriksaan ini, tautan pada halaman publik
     * mengarah ke berkas hantu dan menghasilkan 404.
     */
    public function getHasFileAttribute(): bool
    {
        if (empty($this->file_path)) {
            return false;
        }

        return Storage::disk('public')->exists($this->file_path);
    }

    /**
     * URL publik berkas, atau null bila berkasnya tidak ada.
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->has_file ? Storage::disk('public')->url($this->file_path) : null;
    }

}
