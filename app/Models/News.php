<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;


class News extends Model
{
    protected $guarded = [];

    public function getImageUrlAttribute(): string
    {
        // Cek apakah ada nama file DAN file tersebut benar-benar ada di storage
        if ($this->image && File::exists(public_path('uploads/' . $this->image))) {
            return asset('uploads/' . $this->image);
        }

        // Jika tidak, kembalikan URL gambar cadangan
        return asset('/assets_landing/img/bandara/APT04947.JPG');
    }
}
