<?php

namespace App\Http\Controllers\Staff_User;

use DataTables;
use App\Models\Letter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class LetterController extends Controller
{
    public function index()
    {
        $letters = Letter::latest()->get();
        return view('user_staff2.regulasi.index', compact('letters'));
    }

    public function create()
    {
        return view('user_staff2.regulasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:edaran,utusan',
            'number' => 'required|string|unique:letters,number',
            'title' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'file' => 'required|file|mimes:pdf|max:5120', // Maks 5MB
        ]);

        $filePath = $request->file('file')->store('uploads/letters', 'public');

        Letter::create([
            'type' => $request->type,
            'number' => $request->number,
            'title' => $request->title,
            'issue_date' => $request->issue_date,
            'file_path' => $filePath,
        ]);

        return redirect()->route('letters.staff.index')->with('success', 'Surat berhasil ditambahkan.');
    }
    

    public function edit(Letter $letter)
    {   
        // dd($letter);
         return view('user_staff2.regulasi.edit', compact('letter'));
    }

    public function update(Request $request, Letter $letter)
    {
        $request->validate([
            'type' => 'required|in:edaran,utusan',
            'number' => 'required|string|unique:letters,number,' . $letter->id,
            'title' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = [
            'type' => $request->type,
            'number' => $request->number,
            'title' => $request->title,
            'issue_date' => $request->issue_date,
        ];

        if ($request->hasFile('file')) {
            // Hapus file lama
            Storage::disk('public')->delete($letter->file_path);
            $data['file_path'] = $request->file('file')->store('uploads/letters', 'public');
        }

        $letter->update($data);

        return redirect()->route('letters.staff.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy(Letter $letter)
    {
        Storage::disk('public')->delete($letter->file_path);
        $letter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Surat berhasil dihapus.'
        ]);
    }


    public function suratEdaran()
    {
        $letters = Letter::where('type', 'edaran')->latest()->get();
        return view('navigation.regulasi.surat-edaran.index', compact('letters'));
    }

    public function suratUtusan()
    {
        $letters = Letter::where('type', 'utusan')->latest()->get();
        return view('navigation.regulasi.surat-utusan.index', compact('letters'));
    }
}