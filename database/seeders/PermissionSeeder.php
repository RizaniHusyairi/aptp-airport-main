<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;


class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'Manajemen Berita',
            'Manajemen Tenant',
            'Manajemen Sewa',
            'Manajemen Perijinan Usaha',
            'Manajemen Pengiklanan',
            'Manajemen Field Trip',
            'Manajemen Laporan Keuangan',
            'Manajemen Slider',
            'Manajemen Ajuan Informasi Publik',
            'Manajemen Lalu Lintas Angkutan Udara',
            'Manajemen Regulasi',
            'Manajemen Pengaduan',
            'Manajemen Lelang',
            'Manajemen Slot Charter',
            
        ];

        foreach ($permissions as $item) {
            $permission = Permission::create([
                'permission_name' => $item,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
