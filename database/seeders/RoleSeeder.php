<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama untuk menghindari duplikasi
        Role::query()->delete();

        // Level 1: Jabatan Tertinggi
        $kepalaBandara = Role::create(['name' => 'Kepala Bandara']);

        // Level 2: Di bawah Kepala Bandara
        $kasubbag = Role::create(['name' => 'Kepala Subbagian Keuangan dan Tata Usaha', 'parent_role_id' => $kepalaBandara->id]);

        // Level 3: Di bawah Kasubbag
        $kasiKeamanan = Role::create(['name' => 'Kepala Seksi Keamanan Penerbangan dan Pelayanan Darurat', 'parent_role_id' => $kasubbag->id]);
        $kasiPelayanan = Role::create(['name' => 'Kepala Seksi Pelayanan dan Kerjasama', 'parent_role_id' => $kasubbag->id]);
        $kasiTeknik = Role::create(['name' => 'Kepala Seksi Teknik dan Operasi', 'parent_role_id' => $kasubbag->id]);

        // Level 4: Jabatan Staff umum (atasan bisa bervariasi)
        $staffKeamanan = Role::create(['name' => 'Staff Keamanan', 'parent_role_id' => $kasiKeamanan->id]);
        $staffPelayanan = Role::create(['name' => 'Staff Pelayanan', 'parent_role_id' => $kasiPelayanan->id]);
        $staffTeknik = Role::create(['name' => 'Staff Teknik', 'parent_role_id' => $kasiTeknik->id]);
    }
}
