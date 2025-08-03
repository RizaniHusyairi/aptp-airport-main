<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
            ->get();

    return view('admin2.users.index', compact('users'));
}


    public function show(Request $request, User $user)
    {
        $roles = Role::with('permissions')->get(); 
        $userRoles = $user->roles->pluck('id')->toArray();
        


        if ($request->ajax()) {
            return Datatables::of($user->tickets)->addIndexColumn()
                ->setRowClass(fn ($row) => 'align-middle')
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex justify-content-center">';
                    $td .= '<button data-id="' . $row->id . '" type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light me-1 cancel-btn" disabled>Chanage Status</button>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->addColumn('status', function ($row) {

                    return '<span class="badge badge-pill badge-soft-' . getStatusColor($row->status) . ' font-size-14">' . $row->status . '</span>';
                })
                ->editColumn('flight_info', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="">';
                    $td .= '<p class="fw-bold">' . __('translation.flight.flight_number') . ': <span class="fw-normal">' . $row->flight->flight_number . '</span></p>';
                    $td .= '<p class="fw-bold">' . __('translation.flight.plane_code') . ': <span class="fw-normal">' . $row->flight->plane->code . '</span></p>';
                    $td .= '<p class="fw-bold">' . __('translation.flight.airline') . ': <span class="fw-normal">' . $row->flight->airline->name . '</span></p>';
                    $td .= '<p class="fw-bold">' . __('translation.flight.price') . ': <span class="fw-normal">' . formatPrice($row->flight->price) . '</span></p>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn('route', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="">';
                    $td .= '<p class="fw-bold">' . __('translation.flight.origin') . ': <span class="fw-normal">' . airportName($row->flight->origin->name) . '</span></p>';
                    $td .= '<p class="fw-bold">' . __('translation.flight.destination') . ': <span class="fw-normal">' . airportName($row->flight->destination->name) . '</span></p>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn('time', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="">';
                    $td .= '<p class="fw-bold">' . __('translation.flight.departure') . ': <span class="fw-normal">' . formatDateWithTimezone($row->flight->departure) . '</span></p>';
                    $td .= '<p class="fw-bold">' . __('translation.flight.arrival') . ': <span class="fw-normal">' . formatDateWithTimezone($row->flight->arrival) . '</span></p>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->rawColumns(['flight_info', 'route', 'time', 'action', 'status'])
                ->make(true);
        }

        return view('admin2.users.show', compact('user', 'roles', 'userRoles'));
    }
    
    public function updateRole(Request $request, User $user)
    {
        // Ambil semua ID role yang dipilih (bernilai on)
        $selectedRoles = array_keys($request->roles ?? []);
        $user->roles()->sync($selectedRoles);

        return redirect()->route('customers.show', $user)->with('success', 'Role berhasil diperbarui.');
    }
}
