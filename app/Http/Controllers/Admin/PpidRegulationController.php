<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidRegulation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PpidRegulationController extends Controller
{
    protected $categories = [
        'Peraturan Undang-undang',
        'Peraturan Komisi Informasi Pusat',
        'Peraturan Kementrian Perhubungan Terkait Keterbukaan Informasi Publik'
    ];

    public function index()
    {
        $regulations = PpidRegulation::orderBy('category')->orderBy('published_date', 'desc')->get();
        return view('user_staff2.regulasi-ppid.index', compact('regulations'));
    }

    public function create()
    {
        return view('user_staff2.regulasi-ppid.create', ['categories' => $this->categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => ['required', Rule::in($this->categories)],
            'published_date' => 'nullable|date',
            'document_link' => 'required|url',
        ]);

        PpidRegulation::create($validated);
        return redirect()->route('staff.ppid-regulations.index')->with('success', 'Regulasi PPID berhasil ditambahkan.');
    }

    public function edit(PpidRegulation $ppidRegulation)
    {
        return view('user_staff2.regulasi-ppid.edit', [
            'regulation' => $ppidRegulation,
            'categories' => $this->categories
        ]);
    }

    public function update(Request $request, PpidRegulation $ppidRegulation)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => ['required', Rule::in($this->categories)],
            'published_date' => 'nullable|date',
            'document_link' => 'required|url',
        ]);

        $ppidRegulation->update($validated);
        return redirect()->route('staff.ppid-regulations.index')->with('success', 'Regulasi PPID berhasil diperbarui.');
    }

    public function destroy(PpidRegulation $ppidRegulation)
    {
        $ppidRegulation->delete();
        return redirect()->route('staff.ppid-regulations.index')->with('success', 'Regulasi PPID berhasil dihapus.');
    }
}