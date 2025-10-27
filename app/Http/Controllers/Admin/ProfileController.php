<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Log;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        $data = [
            'is_staff' => $user->is_staff,
            'is_admin' => $user->is_admin,
            'permissions' => $user->getAllPermissions(), // sesuai method sebelumnya
        ];
        $colorMap = [
                    'Manajemen Tenant' => 'primary',
                    'Manajemen Sewa' => 'success',
                    'Manajemen Perijinan Usaha' => 'warning',
                    'Manajemen Pengiklanan' => 'info',
                    'Manajemen Field Trip' => 'secondary',
                    'Manajemen Berita' => 'danger',
                    'Manajemen Kinerja Keuangan' => 'dark',
                    'Manajemen Perijinan Kerja' => 'light',
                    'Manajemen Ajuan Informasi Publik' => 'primary',
                    'Manajemen Lalu Lintas Angkutan Udara' => 'danger',
                    'Manajemen Regulasi' => 'success',
                    'Manajemen Lelang' => 'info',
                    'Manajemen Pengaduan' => 'secondary',
                    'Manajemen Slot Charter' => 'warning',
                    'Manajemen Extend Advance' => 'primary',
                    'Manajemen Inventaris' => 'secondary',
                    'Manajemen Suku Cadang' => 'warning',
                    'Permintaan Suku Cadang' => 'warning',
                    'Manajemen Program Kerja' => 'light',
                ];

        // $lastFlights = Flight::latest()->take(5)->get();

        return view('admin2.profile.index', compact('data', 'colorMap'));
    }

    public function updateProfile(Request $request)
    {
       $user = auth()->user();

        $validator = Validator::make($request->all(), [
            "name" => ['required', 'string', 'max:255'],
            "email" => ['required', 'email', 'unique:users,email,' . $user->id],
            "address" => ['nullable', 'string'],
            "phone" => ['required', 'string', 'unique:users,phone,' . $user->id],
            "avatar" => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // Validasi untuk avatar
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        try {
            // Update data teks terlebih dahulu
            $user->update($validator->safe()->except('avatar'));
            $validated = $validator->validated();
            
            if ($request->hasFile('avatar')) {
                // Hapus avatar lama (jika ada) dan tambahkan yang baru
                $user->clearMediaCollection('avatars');
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
            }
            $user->refresh();

            $user->update($validated);

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'phone' => $user->phone,
                'avatar_url' => $user->avatar_url, // Gunakan accessor baru
            ];

            return response()->json([
                'success' => true, 
                'message' => 'Profil berhasil diperbarui.', 
                'data' => $data
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan internal server.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "current_password" => ['required', 'string'],
            "new_password" => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return response()->json([
                'errors' => ['current_password' => ['Kata sandi saat ini tidak cocok.']]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        auth()->user()->update([
            "password" => Hash::make($request->new_password)
        ]);

        return response()->json(['success' => true, 'message' => 'Kata sandi berhasil diperbarui.'], Response::HTTP_OK);
    }
}
