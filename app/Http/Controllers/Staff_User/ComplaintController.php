<?php

namespace App\Http\Controllers\Staff_User;

use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::all();
        return view('staff_user.pengaduan.index', compact('complaints'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:pending,processed,resolved',
        ]);

        $complaint->update([
            'status' => $request->status,
        ]);

        return redirect()->route('staffComplaints.index')->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return redirect()->route('staffComplaints.index')->with('success', 'Pengaduan berhasil dihapus.');
    }    

}


