<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HeroSettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan hero section.
     */
    public function index()
    {
        // Ambil pengaturan saat ini dari database
        $settings = Setting::whereIn('key', [
            'hero_type',
            'hero_image_path',
            'hero_video_path'
        ])->pluck('value', 'key');

        return view('user_staff2.slider.index', compact('settings'));
    }

    /**
     * Menyimpan atau memperbarui pengaturan hero section.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_type' => ['required', Rule::in(['image', 'video'])],
            'hero_image' => 'nullable|required_if:hero_type,image|image|mimes:jpeg,png,jpg,webp|max:5120', // Maks 5MB
            'hero_video' => 'nullable|required_if:hero_type,video|file|mimes:mp4,webm|max:20480', // Maks 20MB
        ],[
            'hero_image.required_if' => 'File gambar wajib diunggah jika tipe hero adalah Gambar.',
            'hero_video.required_if' => 'File video wajib diunggah jika tipe hero adalah Video.',
            'hero_image.max' => 'Ukuran gambar maksimal 5MB.',
            'hero_video.max' => 'Ukuran video maksimal 20MB.',
            'hero_video.mimes' => 'Format video harus MP4 atau WEBM.',
        ]);

        // Simpan tipe hero
        Setting::updateOrCreate(
            ['key' => 'hero_type'],
            ['value' => $validated['hero_type']]
        );

        // Proses upload gambar jika ada
        if ($request->hasFile('hero_image') && $validated['hero_type'] === 'image') {
            // Hapus file lama jika ada
            $oldImage = Setting::where('key', 'hero_image_path')->value('value');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            // Simpan file baru
            $imagePath = $request->file('hero_image')->store('hero-background', 'public');
            Setting::updateOrCreate(
                ['key' => 'hero_image_path'],
                ['value' => $imagePath]
            );
            // Hapus path video lama jika beralih ke gambar
            Setting::updateOrCreate(['key' => 'hero_video_path'], ['value' => null]);

        } elseif ($validated['hero_type'] === 'video') {
             // Jika memilih video tapi tidak mengunggah file baru, JANGAN hapus path video lama
            if ($request->hasFile('hero_video')) {
                 // Hapus file video lama jika ada
                $oldVideo = Setting::where('key', 'hero_video_path')->value('value');
                if ($oldVideo) {
                    Storage::disk('public')->delete($oldVideo);
                }
                 // Simpan file video baru
                $videoPath = $request->file('hero_video')->store('hero-background', 'public');
                Setting::updateOrCreate(
                    ['key' => 'hero_video_path'],
                    ['value' => $videoPath]
                );
                 // Hapus path gambar lama jika beralih ke video
                Setting::updateOrCreate(['key' => 'hero_image_path'], ['value' => null]);
            }
        }


        return redirect()->route('admin.hero-settings.index')->with('success', 'Pengaturan latar belakang hero berhasil diperbarui.');
    }
}
