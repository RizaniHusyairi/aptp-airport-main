<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use App\Models\OjtStudent;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class OjtStudentController extends Controller
{
    public function index()
    {
        $students = OjtStudent::latest()->get();
        return view('user_staff2.ojt.index', compact('students'));
    }

    public function create()
    {
        // Daftar Unit Kerja untuk Checkbox
        $units = [
            'Kepegawaian', 'Tata Usaha', 'AAB', 'Keuangan', 'Jasa', 'Avsec',
            'Bendahara', 'Bangland', 'AMC', 'Data & Informasi', 'Elband',
            'Pengelola Informasi', 'BMN', 'Listrik', 'Humas', 'PKP-PK'
        ];
        return view('user_staff2.ojt.create', compact('units'));
    }

    public function show(OjtStudent $ojt) // Gunakan Model Binding
    {
        // dd($ojtStudent);
        $student = $ojt;
        // $ojtStudent otomatis diambil berdasarkan ID dari URL
        return view('user_staff2.ojt.show', compact('student'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_number' => 'required|string|max:50',
            'birth_place' => 'required|string',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'institution' => 'required|string',
            'major' => 'required|string',
            'duration' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'phone_number' => 'required|string',

            // Array Validation
            'supervisors' => 'required|array|min:1',
            'supervisors.*' => 'required|string',
            'work_units' => 'required|array|min:1',

            // File Validation
            'identity_card' => 'required|image|max:2048',
            'photo' => 'required|image|max:2048', // 4x6 merah
        ]);

        // Upload Files
        $idPath = $request->file('identity_card')->store('ojt_docs/identity', 'public');
        $photoPath = $request->file('photo')->store('ojt_docs/photos', 'public');

        OjtStudent::create([
            'name' => $validated['name'],
            'id_number' => $validated['id_number'],
            'birth_place' => $validated['birth_place'],
            'birth_date' => $validated['birth_date'],
            'address' => $validated['address'],
            'institution' => $validated['institution'],
            'major' => $validated['major'],
            'duration' => $validated['duration'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'supervisors' => $validated['supervisors'], // Simpan array langsung (dicast model)
            'work_units' => $validated['work_units'],
            'phone_number' => $validated['phone_number'],
            'identity_card_path' => $idPath,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('staff.ojt.index')->with('success', 'Data Mahasiswa OJT berhasil disimpan.');
    }

    public function exportCertificate(OjtStudent $student)
    {
        // Setup PDF (Landscape Certificate)
        $pdf = PDF::loadView('user_staff2.ojt.certificate_pdf', compact('student'));
        $pdf->setPaper('A4', 'landscape');

        $filename = 'Sertifikat-OJT-' . Str::slug($student->name) . '.pdf';
        return $pdf->stream($filename); // Stream agar bisa dipreview dulu
    }

    public function destroy(OjtStudent $ojt)
    {
        if($ojt->identity_card_path) Storage::disk('public')->delete($ojt->identity_card_path);
        if($ojt->photo_path) Storage::disk('public')->delete($ojt->photo_path);

        $ojt->delete();
        return back()->with('success', 'Data berhasil dihapus');
    }
}
