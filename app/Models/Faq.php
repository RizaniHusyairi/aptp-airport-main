<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'service_id' => 'integer',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Jawaban tanpa tag HTML, dipakai untuk atribut pencarian dan JSON-LD
     * (Google menolak HTML mentah pada field text FAQPage).
     */
    public function getPlainAnswerAttribute()
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags($this->answer)));
    }
}
