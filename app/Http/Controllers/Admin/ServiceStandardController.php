<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceStandard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ServiceStandardController extends Controller
{
    /**
     * Jenis dokumen yang diwajibkan UU 25/2009 tentang Pelayanan Publik.
     */
    protected $types = [
        'Standar Pelayanan',
        'Maklumat Pelayanan',
        'Survei Kepuasan Masyarakat',
    ];

    /**
     * Folder penyimpanan pada disk 'public' (root: public/uploads).
     */
    const UPLOAD_DIR = 'standar-pelayanan';

    public function index()
    {
        $documents = ServiceStandard::latest()->get();
        return view('user_staff2.standar-pelayanan.index', compact('documents'));
    }

    public function create()
    {
        return view('user_staff2.standar-pelayanan.create', [
            'types' => $this->types,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $data = $this->prepareData($request, $validated);

        ServiceStandard::create($data);

        return redirect()->route('staff.service-standards.index')
            ->with('success', 'Dokumen standar pelayanan berhasil ditambahkan.');
    }

    public function edit(ServiceStandard $serviceStandard)
    {
        return view('user_staff2.standar-pelayanan.edit', [
            'document' => $serviceStandard,
            'types' => $this->types,
        ]);
    }

    public function update(Request $request, ServiceStandard $serviceStandard)
    {
        $validated = $request->validate($this->rules($serviceStandard));

        $data = $this->prepareData($request, $validated, $serviceStandard);

        $serviceStandard->update($data);

        return redirect()->route('staff.service-standards.index')
            ->with('success', 'Dokumen standar pelayanan berhasil diperbarui.');
    }

    public function destroy(ServiceStandard $serviceStandard)
    {
        if ($serviceStandard->file_path) {
            Storage::disk('public')->delete($serviceStandard->file_path);
        }

        $serviceStandard->delete();

        return redirect()->route('staff.service-standards.index')
            ->with('success', 'Dokumen standar pelayanan berhasil dihapus.');
    }

    /**
     * Aturan validasi bersama untuk store & update.
     *
     * @param  \App\Models\ServiceStandard|null  $model  Data lama saat update.
     */
    protected function rules(ServiceStandard $model = null)
    {
        $hasExistingFile = $model && $model->file_path;

        return [
            'type' => ['required', Rule::in($this->types)],
            'title' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'published_date' => 'required|date',
            'is_active' => 'nullable|boolean',
            'source_type' => 'required|in:upload,link',

            // File wajib saat mode unggah, kecuali sudah ada file lama yang dipertahankan.
            'file' => [
                Rule::requiredIf(function () use ($hasExistingFile) {
                    return request('source_type') === 'upload' && ! $hasExistingFile;
                }),
                'nullable', 'file', 'mimes:pdf', 'max:10240', // Maks 10MB
            ],
            'document_link' => 'required_if:source_type,link|nullable|url',
        ];
    }

    /**
     * Susun atribut yang akan disimpan, termasuk penanganan perpindahan
     * antara mode unggah dan mode tautan.
     */
    protected function prepareData(Request $request, array $validated, ServiceStandard $model = null)
    {
        $sourceType = $validated['source_type'];
        unset($validated['source_type'], $validated['file']);

        $validated['is_active'] = $request->boolean('is_active');

        if ($sourceType === 'upload') {
            if ($request->hasFile('file')) {
                // Ganti file: buang yang lama agar tidak menumpuk di disk.
                if ($model && $model->file_path) {
                    Storage::disk('public')->delete($model->file_path);
                }
                $validated['file_path'] = $request->file('file')->store(self::UPLOAD_DIR, 'public');
            }
            // Tanpa file baru, file_path lama dipertahankan (tidak diikutkan di $validated).
            $validated['document_link'] = null;
        } else {
            // Beralih ke tautan: hapus file lama dari disk.
            if ($model && $model->file_path) {
                Storage::disk('public')->delete($model->file_path);
            }
            $validated['file_path'] = null;
            $validated['document_link'] = $request->document_link;
        }

        return $validated;
    }
}
