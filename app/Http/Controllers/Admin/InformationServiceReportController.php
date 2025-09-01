<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InformationServiceReport;
use Illuminate\Http\Request;

class InformationServiceReportController extends Controller
{
    public function index()
    {
        $reports = InformationServiceReport::orderBy('publication_year', 'desc')->get();
        return view('user_staff2.laporan-layanan-informasi.index', compact('reports'));
    }

    public function create()
    {
        return view('user_staff2.laporan-layanan-informasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'publication_year' => 'required|integer|digits:4|min:2000|max:' . (date('Y') + 1),
            'document_link' => 'required|url',
        ]);

        InformationServiceReport::create($validated);
        return redirect()->route('staff.information-service-reports.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function edit(InformationServiceReport $informationServiceReport)
    {
        return view('user_staff2.laporan-layanan-informasi.edit', ['report' => $informationServiceReport]);
    }

    public function update(Request $request, InformationServiceReport $informationServiceReport)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'publication_year' => 'required|integer|digits:4|min:2000|max:' . (date('Y') + 1),
            'document_link' => 'required|url',
        ]);

        $informationServiceReport->update($validated);
        return redirect()->route('staff.information-service-reports.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(InformationServiceReport $informationServiceReport)
    {
        $informationServiceReport->delete();
        return redirect()->route('staff.information-service-reports.index')->with('success', 'Laporan berhasil dihapus.');
    }
}