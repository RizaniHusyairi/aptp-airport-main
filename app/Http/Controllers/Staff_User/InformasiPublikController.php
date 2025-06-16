<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\PublicInformation;
use Illuminate\Support\Facades\Validator;

class InformasiPublikController extends Controller
{
    public function index()
    {
        $publicInformation = PublicInformation::latest()->get();
        return view('user_staff2.informasi-publik.index', compact('publicInformation'));
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
}
