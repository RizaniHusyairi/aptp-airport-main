<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Facility extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'details',
        'image_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'details' => 'array', // Otomatis ubah JSON ke array dan sebaliknya
    ];

    public function getImageUrlAttribute(): string
    {
        // Cek apakah path gambar ada DAN file-nya benar-benar ada di storage
        if ($this->image_path && file_exists(public_path('uploads/' . $this->image_path))) {
            return asset('uploads/' . $this->image_path);
            
        }

        // Jika tidak, kembalikan URL placeholder berdasarkan kategori
        switch ($this->category) {
            case 'udara':
                return 'https://placehold.co/600x400/0d2c4a/ffffff?text=Sisi+Udara';
            case 'darat':
                return 'https://placehold.co/600x400/0d2c4a/ffffff?text=Sisi+Darat';
            case 'umum':
                return 'https://placehold.co/600x400/0d2c4a/ffffff?text=Fasilitas+Umum';
            default:
                return 'https://placehold.co/600x400/cccccc/ffffff?text=Fasilitas';
        }
    }
}
