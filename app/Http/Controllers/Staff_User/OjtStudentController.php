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

    /**
     * Menampilkan halaman edit.
     */
    public function edit(OjtStudent $ojt)
    {
        // Daftar Unit Kerja (Sama seperti create)
        $units = [
            'Kepegawaian', 'Tata Usaha', 'AAB', 'Keuangan', 'Jasa', 'Avsec',
            'Bendahara', 'Bangland', 'AMC', 'Data & Informasi', 'Elband',
            'Pengelola Informasi', 'BMN', 'Listrik', 'Humas', 'PKP-PK'
        ];
        $student = $ojt;
        return view('user_staff2.ojt.edit', compact('student', 'units'));
    }

    /**
     * Memperbarui data di database.
     */
    /**
     * Memperbarui data di database.
     */
    public function update(Request $request, OjtStudent $ojt)
    {
        $student = $ojt;
        // 1. Definisi Aturan (Rules)
        $rules = [
            'name'          => 'required|string|max:255',
            'id_number'     => 'required|string|max:50',
            'birth_place'   => 'required|string',
            'birth_date'    => 'required|date',
            'address'       => 'required|string',
            'institution'   => 'required|string',
            'major'         => 'required|string',
            'duration'      => 'required|string',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date',
            'phone_number'  => 'required|string',
            'supervisors'   => 'required|array|min:1',
            'supervisors.*' => 'required|string',
            'work_units'    => 'required|array|min:1',

            // File saat update bersifat 'nullable' (boleh kosong)
            'identity_card' => 'nullable|image|max:2048',
            'photo'         => 'nullable|image|max:2048',
        ];

        // 2. Pesan Error Bahasa Indonesia
        $messages = [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            'date'     => 'Format tanggal tidak valid.',
            'max'      => [
                'string' => ':attribute maksimal :max karakter.',
                'file'   => 'Ukuran file :attribute maksimal 2MB.', // 2048KB = 2MB
            ],
            'image'    => 'File :attribute harus berupa gambar (jpg, jpeg, png).',
            'array'    => ':attribute harus berupa data list.',
            'min'      => [
                'array' => 'Minimal harus memilih satu :attribute.',
            ],
        ];

        // 3. Nama Atribut Custom (Agar pesan error menyebut nama kolom dengan benar)
        $attributes = [
            'name'          => 'Nama Lengkap',
            'id_number'     => 'Nomor KTP/Kartu Pelajar',
            'birth_place'   => 'Tempat Lahir',
            'birth_date'    => 'Tanggal Lahir',
            'address'       => 'Alamat Lengkap',
            'institution'   => 'Asal Sekolah/Kampus',
            'major'         => 'Jurusan',
            'duration'      => 'Lama OJT',
            'start_date'    => 'Tanggal Mulai',
            'end_date'      => 'Tanggal Selesai',
            'phone_number'  => 'Nomor Handphone',
            'supervisors'   => 'Nama Pembimbing',
            'work_units'    => 'Unit Kerja',
            'identity_card' => 'Scan KTP',
            'photo'         => 'Pas Foto',
        ];

        // Eksekusi Validasi
        $validated = $request->validate($rules, $messages, $attributes);

        // Cek apakah ada file KTP baru diupload
        if ($request->hasFile('identity_card')) {
            // Hapus file lama jika ada
            if ($student->identity_card_path) {
                Storage::disk('public')->delete($student->identity_card_path);
            }
            // Simpan yang baru
            $validated['identity_card_path'] = $request->file('identity_card')->store('ojt_docs/identity', 'public');
        }

        // Cek apakah ada Foto baru diupload
        if ($request->hasFile('photo')) {
            // Hapus file lama
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }
            // Simpan yang baru
            $validated['photo_path'] = $request->file('photo')->store('ojt_docs/photos', 'public');
        }

        $student->update($validated);

        return redirect()->route('staff.ojt.index')->with('success', 'Data Mahasiswa OJT berhasil diperbarui.');
    }

    public function updateGrades(Request $request, OjtStudent $student)
    {
        // 1. Validasi input array
        $request->validate([
            'types' => 'required|array',       // <-- Input Baru (Kategori)
            'components' => 'required|array',
            'scores' => 'required|array',
            'scores.*' => 'numeric|min:0|max:100',
        ]);

        $grades = [];
        $totalScore = 0;
        $count = 0;

        // 2. Looping data
        foreach ($request->components as $index => $component) {
            // Pastikan data komponen dan nilai ada di index tersebut
            if (!empty($component) && isset($request->scores[$index])) {

                $score = $request->scores[$index];
                $type = $request->types[$index] ?? 'Umum'; // Default 'Umum' jika kosong

                // 3. Simpan struktur data baru
                $grades[] = [
                    'type' => $type,           // Simpan Tipe (Hard Skill/Soft Skill)
                    'component' => $component, // Simpan Komponen
                    'score' => $score          // Simpan Nilai
                ];

                $totalScore += $score;
                $count++;
            }
        }

        // 4. Hitung Rata-rata & Predikat
        $average = $count > 0 ? $totalScore / $count : 0;

        if ($average >= 90) {
            $letter = 'A'; $predicate = 'Sangat Memuaskan';
        } elseif ($average >= 80) {
            $letter = 'B'; $predicate = 'Baik';
        } elseif ($average >= 70) {
            $letter = 'C'; $predicate = 'Cukup';
        } else {
            $letter = 'D'; $predicate = 'Kurang';
        }

        $student->update([
            'grades' => $grades,
            'average_score' => $average,
            'letter_grade' => $letter,
            'predicate' => $predicate
        ]);

        return back()->with('success', 'Penilaian berhasil disimpan. Predikat: ' . $predicate);
    }

    // Method untuk Staff memfinalisasi pengajuan
    public function finalize(Request $request, OjtStudent $student)
    {
        // Opsi 1: Staff upload file manual yang sudah ditandatangani
        if ($request->hasFile('signed_file')) {
            $path = $request->file('signed_file')->store('ojt_docs/certificates', 'public');
            $student->update([
                'status' => 'Selesai',
                'final_certificate_path' => $path
            ]);
        } 
        // Opsi 2: Staff hanya approve (Sertifikat digenerate sistem by QR Code)
        else {
             $student->update([
                'status' => 'Selesai'
            ]);
        }

        return back()->with('success', 'Status pengajuan diubah menjadi Selesai.');
    }
}
