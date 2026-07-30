<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceStandard extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'published_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * URL dokumen, baik hasil unggahan maupun tautan eksternal.
     */
    public function getDocumentUrlAttribute()
    {
        return $this->file_path
            ? Storage::disk('public')->url($this->file_path)
            : $this->document_link;
    }

    /**
     * True jika dokumen tersimpan di server, false jika berupa tautan eksternal.
     */
    public function getIsUploadedAttribute()
    {
        return (bool) $this->file_path;
    }
}
