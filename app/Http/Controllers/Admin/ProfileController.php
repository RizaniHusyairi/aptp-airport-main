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
                    'Manajemen Laporan Keuangan' => 'dark',
                    'Manajemen Slider' => 'light',
                    'Manajemen Ajuan Informasi Publik' => 'primary',
                    'Manajemen Lalu Lintas Angkutan Udara' => 'danger',
                    'Manajemen Regulasi' => 'success',
                    'Manajemen Lelang' => 'info',
                    'Manajemen Pengaduan' => 'secondary',
                    'Manajemen Slot Charter' => 'warning',
                ];

        // $lastFlights = Flight::latest()->take(5)->get();

        return view('admin2.profile.index', compact('data', 'colorMap'));
    }

    public function updateProfile(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            "name" => ['sometimes', 'required'],
            "email" => ['sometimes', 'required', 'email', 'unique:users,email,' . auth()->id()],
            "address" => ['sometimes',],
            "phone" => ['sometimes', 'unique:users,phone,' . auth()->id()],
        ]);

        
        if ($validator->fails()) {
            return $this->josnResponse(false, __('api.invalid_inputs'), Response::HTTP_UNPROCESSABLE_ENTITY, null, $validator->errors()->all());
        }
        
        try {
            // Retirve the validated input data 
            $validated = $validator->validated();

            $user = auth()->user();

            // Update the user
            $user->update($validated);

            

            $data  = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'phone' => $user->phone
            ];

            return $this->josnResponse(true, __('messages.success'), Response::HTTP_OK, $data);
        } catch (\Throwable $th) {
            return $this->josnResponse(true, __('api.internal_server_error'), Response::HTTP_INTERNAL_SERVER_ERROR, null, showErrorMessage($th));
        }
    }

    public function updatePassword(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            "current_password" => ['required', 'string', 'min:8'],
            "new_password" => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->josnResponse(false, __('api.invalid_inputs'), Response::HTTP_UNPROCESSABLE_ENTITY, null, $validator->errors());
        }

        try {
            //compare the current password with the password in the database
            if (!Hash::check($request->current_password, auth()->user()->password)) {
                return $this->josnResponse(false, __('api.password_not_match'), Response::HTTP_UNAUTHORIZED);
            }

            // Update the user pasword
            auth()->user()->update([
                "password" => Hash::make($request->new_password)
            ]);

            return $this->josnResponse(true, __('messages.success'), Response::HTTP_OK, auth()->user());
        } catch (\Throwable $th) {
            return $this->josnResponse(true, __('api.internal_server_error'), Response::HTTP_INTERNAL_SERVER_ERROR, null, showErrorMessage($e));
        }
    }
}
