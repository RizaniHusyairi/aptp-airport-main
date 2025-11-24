<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class RoleWorkCategory extends Model
{
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}