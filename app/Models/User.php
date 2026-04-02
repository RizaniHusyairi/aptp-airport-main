<?php

namespace App\Models;

use App\Models\Rental;
use App\Models\Tenant;
use App\Models\Ticket;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles; // <<< 1. IMPORT TRAIT INI



class User extends Authenticatable implements HasMedia
{
    use  Notifiable,  SoftDeletes, InteractsWithMedia;
    use HasRoles;

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
            $user->ads()->delete();
            // Anda bisa tambahkan relasi hasMany lainnya di sini

            // Lepaskan semua data dari relasi many-to-many
            $user->rentals()->delete();
            $user->tenants()->delete();

            
            $user->licenses()->delete();
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
        return asset('assetsv2/compiled/jpg/1.png');
    }



    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
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
     * Mendefinisikan relasi ke bawahan pengguna (subordinates).
     * Seorang atasan bisa memiliki banyak bawahan.
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(User::class, 'supervisor_id');
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
        return $this->hasMany(Tenant::class);
    }

    public function fieldtrips()
    {
        return $this->hasMany(Fieldtrip::class);
    }

    public function lelangs()
    {
        return $this->hasMany(Lelang::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function ads()
    {
        return $this->hasMany(Ad::class);
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
