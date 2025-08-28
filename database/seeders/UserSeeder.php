<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        //admin 
        User::firstOrCreate(
            [
                "email" => "admin@aptpairport.id",
                "name" => "admin",
                "phone" => '0123456789',
            ],
            [
                'is_admin' => true,
                'is_accepted' => true,
                "password" => bcrypt("12345",)
            ]
        );

        
        $allPermissions = Permission::pluck('id');

        // === BUAT AKUN STAFF ===

        // 1. Kepala Bandara
        $kabandara = User::create([
            'name' => 'I Kadek Yuli Sastrawan, S.Ikom., S.Sit.',
            'email' => 'kabandara@aptpairport.id',
            'password' => Hash::make('12345678'),
            'phone' => '081234567890',
            'is_staff' => true,
            'is_accepted' => true,
        ]);
        $roleKabandara = Role::where('name', 'Kepala Bandara')->first();
        if ($roleKabandara) {
            $kabandara->roles()->attach($roleKabandara->id);
            $roleKabandara->permissions()->attach($allPermissions);
        }
        
        // 2. Kasubbag
        $kasubbag = User::create([
            'name' => 'Zaldi Ardian, A.Md',
            'email' => 'kasubbag@aptpairport.id',
            'password' => Hash::make('12345678'),
            'phone' => '081234567891',
            'is_staff' => true,
            'is_accepted' => true,
        ]);
        $roleKasubbag = Role::where('name', 'Kepala Subbagian Keuangan dan Tata Usaha')->first();
        if ($roleKasubbag) {
            $kasubbag->roles()->attach($roleKasubbag->id);
            $roleKasubbag->permissions()->attach($allPermissions);
        }

        // 3. Kasi Keamanan
        $kasi1 = User::create([
            'name' => 'Mochamad Ikhsan Fadilah, SE, M.M.Tr',
            'email' => 'kasi1@aptpairport.id',
            'password' => Hash::make('12345678'),
            'phone' => '081234567892',
            'is_staff' => true,
            'is_accepted' => true,
        ]);
        $roleKasi1 = Role::where('name', 'Kepala Seksi Keamanan Penerbangan dan Pelayanan Darurat')->first();
        if ($roleKasi1) {
            $kasi1->roles()->attach($roleKasi1->id);
            $roleKasi1->permissions()->attach($allPermissions);
        }

        // 4. Kasi Pelayanan
        $kasi2 = User::create([
            'name' => 'Roslan, S.E.',
            'email' => 'kasi2@aptpairport.id',
            'password' => Hash::make('12345678'),
            'phone' => '081234567893',
            'is_staff' => true,
            'is_accepted' => true,
        ]);
        $roleKasi2 = Role::where('name', 'Kepala Seksi Pelayanan dan Kerjasama')->first();
        if ($roleKasi2) {
            $kasi2->roles()->attach($roleKasi2->id);
            $roleKasi2->permissions()->attach($allPermissions);
        }
        
        // 5. Kasi Teknik
        $kasi3 = User::create([
            'name' => 'Murdoko, S.H.',
            'email' => 'kasi3@aptpairport.id',
            'password' => Hash::make('12345678'),
            'phone' => '081234567894',
            'is_staff' => true,
            'is_accepted' => true,
        ]);
        $roleKasi3 = Role::where('name', 'Kepala Seksi Teknik dan Operasi')->first();
        if ($roleKasi3) {
            $kasi3->roles()->attach($roleKasi3->id);
            $roleKasi3->permissions()->attach($allPermissions);
        }

        // 6. Staff Teknik
        $staff1 = User::create([
            'name' => 'Staff Teknik 1',
            'email' => 'staff1@aptpairport.id',
            'password' => Hash::make('12345678'),
            'phone' => '081234567895',
            'is_staff' => true,
            'is_accepted' => true,
        ]);
        $roleStaffTeknik = Role::where('name', 'Staff Teknik')->first();
        if ($roleStaffTeknik) {
            $staff1->roles()->attach($roleStaffTeknik->id);
            // Beri beberapa permission acak
            $roleStaffTeknik->permissions()->attach(Permission::inRandomOrder()->limit(5)->pluck('id'));
        }

        // 7. Staff Pelayanan
        $staff2 = User::create([
            'name' => 'Staff Pelayanan 1',
            'email' => 'staff2@aptpairport.id',
            'password' => Hash::make('12345678'),
            'phone' => '081234567896',
            'is_staff' => true,
            'is_accepted' => true,
        ]);
        $roleStaffPelayanan = Role::where('name', 'Staff Pelayanan')->first();
        if ($roleStaffPelayanan) $staff2->roles()->attach($roleStaffPelayanan->id);

        // 8. Staff Keamanan
        $staff3 = User::create([
            'name' => 'Staff Keamanan 1',
            'email' => 'staff3@aptpairport.id',
            'password' => Hash::make('12345678'),
            'phone' => '081234567897',
            'is_staff' => true,
            'is_accepted' => true,
        ]);
        $roleStaffKeamanan = Role::where('name', 'Staff Keamanan')->first();
        if ($roleStaffKeamanan) $staff3->roles()->attach($roleStaffKeamanan->id);


        // === BUAT AKUN PENGAJU ===
        for ($i = 1; $i <= 3; $i++) {
            $pengaju = User::create([
                'name' => 'Pengaju ' . $i,
                'email' => 'pengaju' . $i . '@aptpairport.id',
                'password' => Hash::make('12345678'),
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'is_staff' => false,
                'is_accepted' => true, // Langsung diterima sesuai permintaan
            ]);
            
        }
    }
}
