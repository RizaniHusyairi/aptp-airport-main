<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Kategori bawaan. Hanya saran pada datalist form — validasi tetap
     * menerima kategori baru agar modul bebas ubah kode.
     */
    protected $categories = [
        'Penerbangan & Keberangkatan',
        'Fasilitas Bandara',
        'Layanan & Perizinan',
        'Informasi Publik & Pengaduan',
    ];

    public function index()
    {
        $faqs = Faq::with('service')
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        return view('user_staff2.faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('user_staff2.faq.create', $this->formData());
    }

    public function store(Request $request)
    {
        Faq::create($this->prepareData($request, $request->validate($this->rules())));

        return redirect()->route('staff.faqs.index')
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function edit(Faq $faq)
    {
        return view('user_staff2.faq.edit', $this->formData() + ['faq' => $faq]);
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->update($this->prepareData($request, $request->validate($this->rules())));

        return redirect()->route('staff.faqs.index')
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('staff.faqs.index')
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }

    /**
     * Data pendukung form: saran kategori dan daftar layanan.
     */
    protected function formData()
    {
        return [
            'categories' => collect($this->categories)
                ->merge(Faq::select('category')->distinct()->pluck('category'))
                ->filter()
                ->unique()
                ->values(),
            'services' => Service::where('is_active', true)->orderBy('name')->get(),
        ];
    }

    protected function rules()
    {
        return [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string|max:100',
            'service_id' => 'nullable|exists:services,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];
    }

    protected function prepareData(Request $request, array $validated)
    {
        // Checkbox tidak terkirim saat tidak dicentang, jadi nilainya diturunkan eksplisit.
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order') ?? 0;
        $validated['service_id'] = $request->input('service_id') ?: null;

        return $validated;
    }
}
