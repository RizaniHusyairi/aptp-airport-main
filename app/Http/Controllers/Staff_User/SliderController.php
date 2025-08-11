<?php

namespace App\Http\Controllers\Staff_User;

use Auth;
use App\Models\Slider;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function store(Request $request)

    {
        $request->validate([
            'documents'     => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            
            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.file'         => 'File dokumen tidak valid.',
            'documents.mimes'        => 'Dokumen harus berupa file dengan format: JPG/PNG',
            'documents.max'          => 'Ukuran dokumen maksimal 2MB.',
            
        ]);


        $file = $request->file('documents');

        // Simpan file
        $originalName = $file->getClientOriginalName();
        $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME), '-');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . $safeName . '.' . $extension;
        $filePath = $file->storeAs('documents/slider', $filename, 'public');


        // Simpan data license
        $slider = Slider::create([
            'slider_name' => 'Slider ' . time(),
            'documents'     => $filePath,
        ]);

        return redirect()->route('slider.index')->with('success', 'Pengajuan slider berhasil dikirim!');
    }

    public function create()
    {
        return view('user_staff2.slider.create');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/documents/slider/' . basename($slider->documents));
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }
        // Storage::disk('public')->delete($slider->documents);
        // $slider->delete();

        // Hapus slider
        $slider->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data slide berhasil dihapus.'
        ]);

        }

    public function toggleVisibilityHome(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        // Hitung jumlah slider yang sudah aktif di footer
        $activeHomeCount = Slider::where('is_visible_home', 1)->count();
        
        // Ambil nilai dari request, jika tidak ada, gunakan 0 sebagai default
        $isVisible = $request->input('is_visible_home', 0);

        // ### PERBAIKAN DI SINI ###
        // Gunakan nilai $isVisible yang sudah memiliki default 0
        $slider->is_visible_home = $isVisible;
        $slider->save();

        return back()->with('success', 'Status visibilitas home diperbarui.');
    }

    public function toggleVisibilityFooter(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        // Hitung jumlah slider yang sudah aktif di footer
        $activeFooterCount = Slider::where('is_visible_footer', 1)->count();

        // Cek apakah ada lebih dari 3 slider yang aktif
        if ($activeFooterCount >= 3 && !$slider->is_visible_footer) {
            return back()->with('error', 'Hanya 3 slider yang dapat ditampilkan di footer.');
        }

        // Update status is_visible_footer
        $slider->is_visible_footer = $request->input('is_visible_footer');
        $slider->save();

        return back()->with('success', 'Status visibilitas footer diperbarui.');
    }




    public function index()
    {
        $sliders = Slider::latest()->get();
        return view('user_staff2.slider.index', compact('sliders'));     
    }
}
