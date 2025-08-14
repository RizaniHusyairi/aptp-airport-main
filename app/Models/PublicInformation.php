<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PublicInformation extends Model
{
    protected $table = 'public_informations';

    protected $fillable = [
        'user_id', // Tambahkan user_id
        'ktp',
        'surat_pertanggungjawaban',
        'surat_permintaan',
        'pekerjaan', // Kolom ini tetap ada
        'npwp',      // Kolom ini tetap ada
        'rincian_informasi',
        'tujuan_informasi',
        'cara_memperoleh',
        'cara_salinan',
        'status',
        'link_balasan',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
