<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SkmSettingController extends Controller
{
    /**
     * Key pengaturan tautan Survei Kepuasan Masyarakat pada tabel settings.
     */
    protected $keys = [
        'skm_url',
        'skm_label',
        'skm_is_active',
    ];

    /**
     * Menampilkan formulir pengaturan tautan SKM.
     */
    public function index()
    {
        $settings = Setting::whereIn('key', $this->keys)->pluck('value', 'key');

        return view('admin2.skm-settings.index', compact('settings'));
    }

    /**
     * Menyimpan atau memperbarui pengaturan tautan SKM.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'skm_url' => 'required|url|max:500',
            'skm_label' => 'required|string|max:100',
        ]);

        // Checkbox tidak terkirim saat tidak dicentang, jadi nilainya diturunkan eksplisit.
        $validated['skm_is_active'] = $request->boolean('skm_is_active') ? '1' : '0';

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Wajib: helper skmSetting() memakai rememberForever, tanpa ini perubahan
        // tidak akan pernah terlihat di halaman publik.
        Cache::forget('skm_setting');

        return redirect()->route('admin.skm-settings.index')
            ->with('success', 'Pengaturan tautan Survei Kepuasan Masyarakat berhasil diperbarui.');
    }
}
