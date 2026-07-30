<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExternalLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ExternalLinkController extends Controller
{
    /**
     * Kelompok yang disarankan. Hanya jadi saran pada datalist form —
     * validasi tetap menerima kelompok baru agar modul bebas ubah kode.
     */
    protected $groups = [
        'Layanan Pengaduan & Informasi Publik',
        'Aplikasi Internal Pegawai',
    ];

    /**
     * Folder penyimpanan pada disk 'public' (root: public/uploads).
     */
    const UPLOAD_DIR = 'tautan-terkait';

    public function index()
    {
        $links = ExternalLink::orderBy('sort_order')->orderBy('name')->get();

        return view('user_staff2.tautan-terkait.index', compact('links'));
    }

    public function create()
    {
        return view('user_staff2.tautan-terkait.create', [
            'groups' => $this->suggestedGroups(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->prepareData($request, $request->validate($this->rules()));

        ExternalLink::create($data);
        Cache::forget('external_links');

        return redirect()->route('staff.external-links.index')
            ->with('success', 'Tautan terkait berhasil ditambahkan.');
    }

    public function edit(ExternalLink $externalLink)
    {
        return view('user_staff2.tautan-terkait.edit', [
            'link' => $externalLink,
            'groups' => $this->suggestedGroups(),
        ]);
    }

    public function update(Request $request, ExternalLink $externalLink)
    {
        $data = $this->prepareData($request, $request->validate($this->rules()), $externalLink);

        $externalLink->update($data);
        Cache::forget('external_links');

        return redirect()->route('staff.external-links.index')
            ->with('success', 'Tautan terkait berhasil diperbarui.');
    }

    public function destroy(ExternalLink $externalLink)
    {
        if ($externalLink->logo_path) {
            Storage::disk('public')->delete($externalLink->logo_path);
        }

        $externalLink->delete();
        Cache::forget('external_links');

        return redirect()->route('staff.external-links.index')
            ->with('success', 'Tautan terkait berhasil dihapus.');
    }

    /**
     * Gabungan kelompok bawaan dan kelompok yang sudah dipakai, untuk datalist.
     */
    protected function suggestedGroups()
    {
        return collect($this->groups)
            ->merge(ExternalLink::select('group')->distinct()->pluck('group'))
            ->filter()
            ->unique()
            ->values();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'url' => 'required|url|max:500',
            'description' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:50',
            'group' => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:1024',
        ];
    }

    /**
     * Susun atribut yang disimpan, termasuk penanganan berkas logo.
     */
    protected function prepareData(Request $request, array $validated, ExternalLink $model = null)
    {
        unset($validated['logo']);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order') ?? 0;

        if ($request->hasFile('logo')) {
            // Ganti logo: buang yang lama agar tidak menumpuk di disk.
            if ($model && $model->logo_path) {
                Storage::disk('public')->delete($model->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store(self::UPLOAD_DIR, 'public');
        } elseif ($request->boolean('remove_logo') && $model && $model->logo_path) {
            Storage::disk('public')->delete($model->logo_path);
            $validated['logo_path'] = null;
        }

        return $validated;
    }
}
