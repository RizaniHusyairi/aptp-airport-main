<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ProfileBandaraSettingController extends Controller
{
    /**
     * Menampilkan formulir pengaturan profil bandara.
     */
    public function index()
    {
        // Ambil pengaturan saat ini dari database
        $settings = Setting::whereIn('key', [
            'profile_sejarah',
            'profile_status',
            'profile_rute',
            'profile_tugas',
            'profile_fungsi',
            'profile_visi',
            'profile_misi',
        ])->pluck('value', 'key');

        return view('admin2.profile-settings.index', compact('settings'));
    }

    /**
     * Menyimpan atau memperbarui pengaturan profil bandara.
     */
    public function update(Request $request)
    {
        $keys = [
            'profile_sejarah',
            'profile_status',
            'profile_rute',
            'profile_tugas',
            'profile_fungsi',
            'profile_visi',
            'profile_misi',
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->input($key)]
            );
        }

        return redirect()->route('admin.profile-bandara-settings.index')->with('success', 'Pengaturan profil bandara berhasil diperbarui.');
    }
}
