<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use App\Models\Rental;
use App\Models\Tenant;
use App\Models\Ticket;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements HasMedia
{
    use  Notifiable,  SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    
    ];

    protected static function booted()
    {
        static::deleting(function ($user) {
            // Hapus semua data yang memiliki relasi one-to-many
            $user->workPermits()->delete();
            // Anda bisa tambahkan relasi hasMany lainnya di sini

            // Lepaskan semua data dari relasi many-to-many
            $user->rentals()->detach();
            $user->tenants()->detach();
            $user->licenses()->detach();
            $user->ads()->detach();
            $user->submissionDocuments()->detach();
            $user->roles()->detach();
        });
    }

        public function getAvatarUrlAttribute()
    {
        // Ambil media pertama dari koleksi 'avatars'
        $avatar = $this->getFirstMedia('avatars');
        if ($avatar) {
            // Jika user punya avatar, kembalikan URL-nya dari storage
            return $avatar->getUrl();
        }
        // Jika tidak, kembalikan URL ke gambar default
        return asset('assetsv2/compiled/jpg/1.jpg');
    }



    public function rentals()
    {
        return $this->belongsToMany(Rental::class, 'rental_user', 'user_id', 'rental_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return formatDate($value);
    }

    // Scopes
    public function scopeCustomer($query)
    {
        return $query->where('is_admin', 0);
    }

    public function scopeAdmin($query)
    {
        return $query->where('is_admin', 1);
    }

    /**
     * Cek apakah user adalah Admin.
     */
    // public function isAdmin(): bool
    // {
    //     return $this->hasRole('Admin');
    // }

    // Relations
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    

    /**
     * Mendefinisikan relasi "hasMany" ke model WorkPermit.
     * Seorang pengguna dapat memiliki banyak izin kerja.
     */
    public function workPermits(): HasMany
    {
        return $this->hasMany(WorkPermit::class);
    }

    public function publicInformations(): HasMany
    {
        return $this->hasMany(PublicInformation::class);
    }


    public function tenants()
    {
        return $this->belongsToMany(Tenant::class);
    }

    public function licenses()
    {
        return $this->belongsToMany(License::class)
                    ->withTimestamps();
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function ads()
    {
        return $this->belongsToMany(Ad::class)
                    ->withTimestamps();
    }
    
    public function submissionDocuments()
    {
        return $this->belongsToMany(SubmissionDocument::class)
                    ->withPivot('tenant_id', 'file_path')
                    ->withTimestamps();
    }

    // Roles and Permissions
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function getAllPermissions()
    {
        // Cek apakah cache sudah terisi. Jika ya, langsung kembalikan.
        if ($this->permissions_cache !== null) {
            return $this->permissions_cache;
        }

        // Jika cache kosong, jalankan query, simpan ke cache, lalu kembalikan.
        // Eager load 'permissions' untuk menghindari query N+1 di sini.
        return $this->permissions_cache = $this->roles()->with('permissions')->get()
            ->flatMap(function ($role) {
                return $role->permissions;
            })
            ->unique('id');
        
    }

    public function hasPermission($permissionName)
    {
        return $this->getAllPermissions()
            ->pluck('permission_name')
            ->contains($permissionName);
    }
    
}
