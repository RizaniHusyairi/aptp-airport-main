<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ExternalLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * URL logo hasil unggahan, atau null bila memakai ikon.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_path ? Storage::disk('public')->url($this->logo_path) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
