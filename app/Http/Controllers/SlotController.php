<?php

namespace App\Http\Controllers;

use App\Models\slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class SlotController extends Controller
{
    /* ================== STAFF ROUTES ================== */
    
    public function index()
    {
        $slots = slot::with('user')->orderBy('created_at', 'desc');
        $slots = $slots->get();
        
        return view('user_staff.slot.index', compact('slots'));
             
    }
    public function show($id)
    {
        $slot = slot::with('user')->findOrFail($id);
        return view('user_staff.slot.show', compact('slot'));
    }
    public function approve($id)
    {
        $slot = slot::findOrFail($id);
        $slot->status = 'disetujui';
        $slot->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject($id)
    {
        $slot = slot::findOrFail($id);
        $slot->status = 'ditolak';
        $slot->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }


    /* ================== USER ROUTES ================== */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nomorRegistrasi' => ['required', 'string', 'max:10', 'regex:/^[A-Z0-9\-]+$/'],
            'tipePesawat' => ['required', 'string', 'max:50'],
            'jadwalKeberangkatan' => ['required', 'date', 'after:now'],
            'jadwalKedatangan' => ['required', 'date', 'after:jadwalKeberangkatan'],
            'bandaraAsal' => ['required', 'string', 'max:4', 'regex:/^[A-Z]{3,4}$/'],
            'bandaraTujuan' => ['required', 'string', 'max:4', 'regex:/^[A-Z]{3,4}$/'],
            'jenisPenerbangan' => ['required', 'in:penumpang,kargo,lainnya'],
            'documents'     => ['required','file','mimes:pdf','max:2048'],
        ], [
            'nomorRegistrasi.required' => 'Nomor registrasi pesawat wajib diisi.',
            'nomorRegistrasi.max' => 'Nomor registrasi maksimal 10 karakter.',
            'nomorRegistrasi.regex' => 'Nomor registrasi hanya boleh berisi huruf, angka, atau tanda hubung.',
            'tipePesawat.required' => 'Tipe pesawat wajib diisi.',
            'tipePesawat.max' => 'Tipe pesawat maksimal 50 karakter.',
            'jadwalKeberangkatan.required' => 'Jadwal keberangkatan wajib diisi.',
            'jadwalKeberangkatan.date' => 'Jadwal keberangkatan harus berupa tanggal yang valid.',
            'jadwalKeberangkatan.after' => 'Jadwal keberangkatan harus setelah waktu saat ini.',
            'jadwalKedatangan.required' => 'Jadwal kedatangan wajib diisi.',
            'jadwalKedatangan.date' => 'Jadwal kedatangan harus berupa tanggal yang valid.',
            'jadwalKedatangan.after' => 'Jadwal kedatangan harus setelah jadwal keberangkatan.',
            'bandaraAsal.required' => 'Bandara asal wajib diisi.',
            'bandaraAsal.max' => 'Bandara asal maksimal 4 karakter.',
            'bandaraAsal.regex' => 'Bandara asal harus berupa kode bandara (3-4 huruf besar).',
            'bandaraTujuan.required' => 'Bandara tujuan wajib diisi.',
            'bandaraTujuan.max' => 'Bandara tujuan maksimal 4 karakter.',
            'bandaraTujuan.regex' => 'Bandara tujuan harus berupa kode bandara (3-4 huruf besar).',
            'jenisPenerbangan.required' => 'Jenis penerbangan wajib dipilih.',
            'jenisPenerbangan.in' => 'Jenis penerbangan tidak valid.',
            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.file'         => 'File dokumen tidak valid.',
            'documents.mimes'        => 'Dokumen harus berupa file dengan format: PDF',
            'documents.max'          => 'Ukuran dokumen maksimal 2MB.',
        ]);

        // Cek apakah jenis penerbangan adalah "lainnya"
        if ($request->jenisPenerbangan === 'lainnya') {
            // Tambahkan validasi khusus untuk jenis penerbangan lainnya
            $validator->after(function ($validator) use ($request) {
                if (empty($request->jenislainnya)) {
                    $validator->errors()->add('jenislainnya', 'Jenis penerbangan lainnya wajib diisi.');
                } elseif (strlen($request->jenislainnya) > 50) {
                    $validator->errors()->add('jenislainnya', 'Jenis penerbangan lainnya maksimal 50 karakter.');
                }
            });
        }


        if ($validator->fails()) {
            // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
            // dan input yang sudah diisi sebelumnya
            // dd($validator->errors());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan file
        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/slot', $filename, 'public');

        
        // Buat pengajuan slot charter
        $slotCharter = slot::create([
            'user_id' => Auth::id(),
            'aircraft_registration' => $request->nomorRegistrasi,
            'aircraft_type' => $request->tipePesawat,
            'departure_schedule' => $request->jadwalKeberangkatan,
            'arrival_schedule' => $request->jadwalKedatangan,
            'origin_airport' => $request->bandaraAsal,
            'destination_airport' => $request->bandaraTujuan,
            'flight_type' => $request->jenisPenerbangan,
            'flight_more' => $request->jenislainnya ?? null,
            'documents' => $filePath,
            'status' => 'diajukan',
        ]);

        

        return redirect()->route('slot.index')->with('success', 'Pengajuan slot charter berhasil dikirim. Menunggu verifikasi admin.');
    }

    public function create()
    {
        return view('user_staff.slot.create');
    }
    public function destroy($id)
    {
        $slot = slot::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $slot->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }
    
        // Hapus relasi user jika menggunakan pivot
        $slot->users()->detach();
    
        // Hapus slot
        $slot->delete();
    
        return redirect()->route('slot.index')->with('success', 'Pengajuan berhasil dihapus.');    }
    
        public function indexUser()
    {
        $slots = slot::where('user_id', Auth::id());
        $slots = $slots->latest()->get();
        

        return view('user_staff.slot.index', compact('slots'));
    

        
    }
}
