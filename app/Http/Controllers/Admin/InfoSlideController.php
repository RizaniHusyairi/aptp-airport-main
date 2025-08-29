<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfoSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfoSlideController extends Controller
{
    public function index()
    {
        $infoSlides = InfoSlide::latest()->get();
        return view('admin2.info-slides.index', compact('infoSlides'));
    }

    public function create()
    {
        return view('admin2.info-slides.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link_url' => 'nullable|url',
            'is_visible' => 'boolean',
        ]);

        $path = $request->file('image')->store('info_slides', 'public');

        InfoSlide::create([
            'image_path' => $path,
            'link_url' => $validated['link_url'],
            'is_visible' => $request->has('is_visible'),
        ]);

        return redirect()->route('admin.info-slides.index')->with('success', 'Slide informasi baru berhasil ditambahkan.');
    }

    public function edit(InfoSlide $infoSlide)
    {
        return view('admin2.info-slides.edit', compact('infoSlide'));
    }

    public function update(Request $request, InfoSlide $infoSlide)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link_url' => 'nullable|url',
            'is_visible' => 'boolean',
        ]);

        $path = $infoSlide->image_path;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($infoSlide->image_path);
            $path = $request->file('image')->store('info_slides', 'public');
        }

        $infoSlide->update([
            'image_path' => $path,
            'link_url' => $validated['link_url'],
            'is_visible' => $request->has('is_visible'),
        ]);

        return redirect()->route('admin.info-slides.index')->with('success', 'Slide informasi berhasil diperbarui.');
    }

    public function destroy(InfoSlide $infoSlide)
    {
        Storage::disk('public')->delete($infoSlide->image_path);
        $infoSlide->delete();
        return redirect()->route('admin.info-slides.index')->with('success', 'Slide informasi berhasil dihapus.');
    }
}
