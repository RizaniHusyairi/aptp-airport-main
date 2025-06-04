<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        return Auth::user()->is_admin ? route('root') : route('profile');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if (!$user->is_accepted) {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'unverified' => 'Akun Anda belum disetujui. Silakan hubungi admin.',
                ]);
            }

            // Login berhasil
            return redirect()->intended($this->redirectPath())->with('success', 'Login Berhasil');
        }

        // Login gagal karena email/password salah
        throw ValidationException::withMessages([
            'credentials' => ['Email atau password salah'],
        ]);
    }
}
