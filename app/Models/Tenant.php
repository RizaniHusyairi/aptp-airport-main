<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    protected $casts = [
        'documents' => 'array',
    ];
    // public function submissionDocuments()
    // {
    //     return $this->belongsToMany(SubmissionDocument::class, 'submission_document_user')
    //                 ->withPivot('user_id', 'file_path')
    //                 ->withTimestamps();
    // }
}
