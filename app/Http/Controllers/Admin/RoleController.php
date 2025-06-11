<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        
        $roles = Role::with('permissions')->get();
        

        return view('admin2.roles.index', compact('roles'));

    }

    public function create(Request $request){
        $permissions = Permission::all(['id', 'permission_name']);

        return view('admin2.roles.create', compact('permissions'));
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['exists:permissions,id'],
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique' => 'Nama role sudah digunakan.',
            'permissions.required' => 'Pilih setidaknya satu izin.',
            'permissions.min' => 'Pilih setidaknya satu izin.',
            'permissions.*.exists' => 'Izin yang dipilih tidak valid.',
        ]);
        

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat!');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id); // Menyertakan permissions yang dimiliki role
        $permissions = Permission::all(); // Mengambil semua permissions
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array|exists:permissions,id',
        ]);

        $role->name = $request->name;

        if ($role->isDirty('name')) {
            $role->save(); // akan update updated_at
        } else {
            $role->touch(); // tetap update updated_at walaupun nama tidak berubah
        }

        // Sink permission many-to-many
        $role->permissions()->sync($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
    }
}
