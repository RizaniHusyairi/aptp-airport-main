<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'rental_name', 'description', 'rental_type', 'documents',
        'submission_status', 'area', 'location', 'quantity', 'design_file'
    ];

    
    public function users()
    {
        return $this->belongsToMany(User::class, 'rental_user', 'rental_id', 'user_id')
                    ->withTimestamps();
    }
    // public function submissionDocuments()
    // {
    //     return $this->belongsToMany(SubmissionDocument::class, 'submission_document_user')
    //                 ->withPivot('user_id', 'file_path')
    //                 ->withTimestamps();
    // }
}
