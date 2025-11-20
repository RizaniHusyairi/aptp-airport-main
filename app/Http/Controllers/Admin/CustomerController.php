<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash; // <<< PENTING: Tambahkan ini

class CustomerController extends Controller
{
    public function toggleStaff(User $user)
    {
        if ($user->is_staff) {
            $user->is_staff = false;
            $user->roles()->detach(); 
        } else {
            $user->is_staff = true;
            $roleStaff = Role::where('name', 'staff')->first();
            if ($roleStaff) {
                $user->roles()->attach($roleStaff->id);
            }
        }

        $user->save();

        return redirect()->route('customers.show', $user->id)
            ->with('success', 'Status staff berhasil diperbarui.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('customers.show', compact('user', 'roles', 'userRoles'));
    }

    public function verify(User $user)
    {
        $user->update(['is_accepted' => 1]);
        return back()->with('success', 'User berhasil diverifikasi.');
    }

    public function unverify(User $user)
    {
        $user->update(['is_accepted' => 0]);
        return back()->with('success', 'Verifikasi user dibatalkan.');
    }

    public function toggleRole(Request $request, User $user)
    {
        $roleId = $request->input('role_id');

        $hasRole = $user->roles()->where('role_id', $roleId)->exists();

        if ($hasRole) {
            $user->roles()->detach($roleId); 
        } else {
            $user->roles()->attach($roleId); 
        }

        return back();
    }

    public function index(Request $request)
{
    
    $users = User::select('id', 'name', 'email', 'phone', 'created_at', 'is_accepted')
            ->where('is_admin', false) // Hanya pengguna non-admin
            ->with('roles')->latest()
            ->get();

    return view('admin2.users.index', compact('users'));
}


    public function show(Request $request, User $user)
    {
        $roles = Role::with('permissions')->get(); 
        $userRoles = $user->roles->pluck('id')->toArray();

        
        return view('admin2.users.show', compact('user', 'roles', 'userRoles'));
    }
    
    public function updateRole(Request $request, User $user)
    {
        // Ambil semua ID role yang dipilih (bernilai on)
        $selectedRoles = $request->input('roles', []);
        $user->roles()->sync($selectedRoles);

        

        return redirect()->route('customers.show', $user)->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * ### METHOD BARU: Untuk memperbarui atasan seorang staff ###
     */
    

    public function destroy(User $user)
    {
        // Hapus user   
        $user->delete();

        return redirect()->route('customers.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * ### METHOD BARU: Reset Password Pengguna ###
     */
    public function resetPassword(User $user)
    {
        // Set password default
        $defaultPassword = 'Apt123';

        $user->update([
            'password' => Hash::make($defaultPassword)
        ]);

        return back()->with('success', 'Password pengguna ' . $user->name . ' berhasil direset menjadi: ' . $defaultPassword);
    }
}
