<?php

namespace App\Http\Controllers\Staff_User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PublicInformation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InformasiPublikController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if ($user->is_staff) {
            $publicInformation = PublicInformation::with('user')->latest()->get();
        } else {
            $publicInformation = $user->publicInformations()->latest()->get();
        }
        return view('user_staff2.informasi-publik.index', compact('publicInformation'));
    }
    // public function index()
    // {
    //     $publicInformation = PublicInformation::latest()->get();
    //     return view('user_staff2.informasi-publik.index', compact('publicInformation'));
    // }
    public function create(){
        return view('user_staff2.informasi-publik.create');
    }
    public function show($id)
    {
        $publicInformation = PublicInformation::where('id', $id)->firstOrFail();
        return view('user_staff2.informasi-publik.show', compact('publicInformation'));
    }

    public function reply(Request $request, $id)
    {
        $publicInformation = PublicInformation::where('id', $id)->firstOrFail();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'link_balasan' => ['required', 'url'],
            'replied_at' => ['required', 'date'],
        ], [
            'link_balasan.required' => 'Link balasan wajib diisi.',
            'link_balasan.url' => 'Link balasan harus berupa URL yang valid.',
            'replied_at.required' => 'Tanggal balasan wajib diisi.',
            'replied_at.date' => 'Tanggal balasan harus berupa tanggal yang valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Perbarui data
        $publicInformation->update([
            'link_balasan' => $request->link_balasan,
            'replied_at' => $request->replied_at,
            'status' => 'Sudah dibalas',
        ]);


        return redirect()->route('informasiPublik.show', $publicInformation->id)
            ->with('success', 'Balasan berhasil disimpan.');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'ktp' => 'required|file|mimes:jpg,png,pdf|max:2048',
            'surat_pertanggungjawaban' => 'required|file|mimes:jpg,png,pdf|max:2048',
            'surat_permintaan' => 'required|string',
            'pekerjaan' => 'required|string|max:255', // Validasi tetap ada
            'npwp' => 'required|string|max:100',      // Validasi tetap ada
            'rincian_informasi' => 'required|string',
            'tujuan_informasi' => 'required|string',
            'cara_memperoleh' => 'required|string',
            'cara_salinan' => 'required|string',
        ],[
            'ktp.required' => 'Scan KTP wajib diunggah.',
            'ktp.file' => 'Scan KTP harus berupa file.',
            'ktp.mimes' => 'Scan KTP harus berupa file dengan format: JPG, PNG, atau PDF.',
            'ktp.max' => 'Ukuran file KTP tidak boleh melebihi 2MB.',
            'surat_pertanggungjawaban.required' => 'Surat pernyataan pertanggung jawaban wajib diunggah.',
            'surat_pertanggungjawaban.file' => 'Surat pernyataan harus berupa file.',
            'surat_pertanggungjawaban.mimes' => 'Surat pernyataan harus berupa file dengan format: JPG, PNG, atau PDF.',
            'surat_pertanggungjawaban.max' => 'Ukuran file surat pernyataan tidak boleh melebihi 2MB.',
            'surat_permintaan.required' => 'Surat Permintaan wajib diisi.',
            'pekerjaan.required' => 'Pekerjaan wajib diisi.',
            'npwp.required' => 'Nomor NPWP wajib diisi.',
            'rincian_informasi.required' => 'Rincian informasi wajib diisi.',
            'tujuan_informasi.required' => 'Tujuan penggunaan informasi wajib diisi.',
            'cara_memperoleh.required' => 'Cara memperoleh informasi wajib dipilih.',
            'cara_salinan.required' => 'Cara mendapat salinan informasi wajib dipilih.']);

        $ktpPath = $request->file('ktp')->store('documents/public_info/ktp', 'public');
        $suratPath = $request->file('surat_pertanggungjawaban')->store('documents/public_info/surat_pertanggungjawaban', 'public');

        Auth::user()->publicInformations()->create(array_merge($validated, [
            'ktp' => $ktpPath,
            'surat_pertanggungjawaban' => $suratPath,
        ]));

        return redirect()->route('informasiPublik.index')->with('success', 'Pengajuan informasi publik berhasil dikirim.');
    }
    
    public function destroy($id)
    {
        // Cari data berdasarkan ID, jika tidak ketemu akan error 404
        $submission = PublicInformation::findOrFail($id);

        // Hapus file dari storage untuk menjaga kebersihan server
        if ($submission->ktp) {
            Storage::disk('public')->delete($submission->ktp);
        }
        if ($submission->surat_pertanggungjawaban) {
            Storage::disk('public')->delete($submission->surat_pertanggungjawaban);
        }
        
        // Hapus record dari database
        $submission->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('informasiPublik.staffIndex')
            ->with('success', 'Pengajuan informasi publik berhasil dihapus.');
    }
}
