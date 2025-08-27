<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class persuratan extends Model
{
    protected $guarded = [];
protected $fillable = [
        'user_id',
        'assigned_to_user_id',
        'letter_type',
        'letter_date',
        'recipient_address',
        'subject',
        'final_approver_id',
        'collaborators',
        'attachments',
    ];

    protected $casts = [
        'letter_date'   => 'date',
        'collaborators' => 'array',
        'attachments'   => 'array',
    ];

    /**
     * Relasi ke pengguna yang membuat surat.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke pengguna yang saat ini ditugaskan untuk menindaklanjuti.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function revisions()
    {
        return $this->hasMany(SuratRevision::class);
    }

    public function events()
    {
        return $this->hasMany(Surat_event::class)->latest();
    }

    /**
     * Relasi ke pejabat final yang akan menandatangani.
     */
    public function finalApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'final_approver_id');
    }

    /**
     * Relasi ke "buku catatan" verifikasi.
     */
    public function verifications(): HasMany
    {
        return $this->hasMany(SuratVerification::class)->orderBy('order');
    }
}
