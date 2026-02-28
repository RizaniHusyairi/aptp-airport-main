<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Tenant;

class TenantController extends Controller
{
    /* ================== USER ROUTES ================== */
    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'description'   => 'required|string',
            'documents'     => 'required|array',
            'documents.*'     => 'required|file|mimes:pdf|max:2048',
            'rental_type'   => 'required|string',
        ], [
            'business_name.required' => 'Nama usaha wajib diisi.',
            'business_name.string'   => 'Nama usaha harus berupa teks.',
            'business_name.max'      => 'Nama usaha maksimal 255 karakter.',

            'business_type.required' => 'Jenis usaha wajib diisi.',
            'business_type.string'   => 'Jenis usaha harus berupa teks.',
            'business_type.max'      => 'Jenis usaha maksimal 255 karakter.',

            'description.required'   => 'Deskripsi usaha wajib diisi.',
            'description.string'     => 'Deskripsi harus berupa teks.',

            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.array'        => 'Format dokumen tidak valid.',
            'documents.*.file'         => 'File dokumen tidak valid.',
            'documents.*.mimes'        => 'Dokumen harus berupa file dengan format: PDF',
            'documents.*.max'          => 'Ukuran dokumen maksimal 2MB.',

            'rental_type.required'   => 'Jenis sewa wajib dipilih.',
            'rental_type.string'     => 'Jenis sewa tidak valid.',
        ]);

        if($request->rental_type === 'Lainnya') {
            // Tambahkan validasi khusus untuk sewa lainnya
            $request->validate([
                'rental_more' => 'required|string|max:150',
            ], [
                'rental_more.required' => 'Jenis sewa lainnya wajib diunggah.',
                'rental_more.max' => 'Jenis sewa maksimal 150 karakter.',
            ]);
        }


        // Simpan file
        $documentPaths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/tenant', $filename, 'public');
                $documentPaths[] = $path;
            }
        }

        // Simpan data tenant
        $tenant = Tenant::create([
            'user_id' => auth()->id(),
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'description'   => $request->description,
            'rental_type'   => $request->rental_type,
            'rental_more'   => $request->rental_more ?? null,
            'documents'     => $documentPaths,
        ]);

        return redirect()->route('tenant.index')->with('success', 'Pengajuan tenant berhasil dikirim!');
    }

    public function create()
    {
        return view('user_staff2.tenant.create');
    }
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);

        // Hapus file dokumen jika ada
        if (is_array($tenant->documents)) {
            foreach ($tenant->documents as $path) {
                if (\Storage::disk('public')->exists($path)) {
                    \Storage::disk('public')->delete($path);
                }
            }
        } elseif (is_string($tenant->documents)) {
            // Fallback for older data format
            if (\Storage::disk('public')->exists($tenant->documents)) {
                \Storage::disk('public')->delete($tenant->documents);
            }
        }
    

    
        // Hapus tenant
        $tenant->delete();
    
        return redirect()->route('tenant.index')->with('success', 'Pengajuan berhasil dihapus.');    }
    
        public function indexUser()
    {
        $user = Auth::user();
        $tenants = $user->tenants()->latest()->get();
        return view('user_staff2.tenant.index', compact('tenants'));    
    }
    
    /* ================== STAFF ROUTES ================== */
    public function index()
    {
        $tenants = Tenant::with('user')->latest()->get();
        return view('user_staff2.tenant.index', compact('tenants'));     
    }
    public function show($id)
    {
        $tenant = Tenant::with('user')->findOrFail($id);
        return view('user_staff2.tenant.show', compact('tenant'));
    }

    public function updateStatus(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'submission_status' => 'required|in:Disetujui,Ditolak,Revisi Diperlukan',
            'staff_notes' => 'required_if:status,Ditolak,Revisi Diperlukan|nullable|string',
            'reply_document_path' => 'required_if:status,Disetujui|nullable|url',
        ], [
            'staff_notes.required_if' => 'Catatan wajib diisi jika status Ditolak atau Minta Revisi.',
            'reply_document_path.required_if' => 'Tautan surat balasan wajib diisi jika status Disetujui.',
            'reply_document_path.url' => 'Input harus berupa tautan (URL) yang valid.',
        ]);

        $tenant->submission_status = $validated['submission_status'];
        $tenant->staff_notes = $validated['staff_notes'];

        if ($validated['submission_status'] === 'Disetujui') {
            $tenant->reply_document_path = $validated['reply_document_path'];
        } else {
            $tenant->reply_document_path = null;
        }

        $tenant->save();

        return redirect()->route('tenant.staffIndex')->with('success', 'Status pengajuan tenant berhasil diperbarui.');
    }
    





}
