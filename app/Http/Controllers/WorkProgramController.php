<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\WorkProgram;
use Illuminate\Http\Request;
use App\Models\RoleWorkCategory;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WorkProgramController extends Controller
{

    // Daftar kategori statis (sama seperti di RoleController)
    private $workCategories = [
        'Alat-alat Besar',
        'Fasilitas Sisi Udara',
        'Fasilitas Sisi Darat',
        'Elektronika Bandara',
        'Listrik',
        'Mekanikal',
        'Fasilitas Keamanan Penerbangan',
        'PKPPK'
    ];

    /**
     * Menampilkan daftar semua program kerja.
     */
    public function index()
    {
        $user = Auth::user();
        $query = WorkProgram::with('tasks')->latest();

        
        // Mari kita cek kategori yang dimiliki user melalui role-nya
        $userCategories = [];
        foreach ($user->roles as $role) {
            $categories = RoleWorkCategory::where('role_id', $role->id)->pluck('category_name')->toArray();
            $userCategories = array_merge($userCategories, $categories);
        }
        $userCategories = array_unique($userCategories);

        // Jika user memiliki kategori spesifik, filter query
        if (!empty($userCategories)) {
            $query->whereIn('category', $userCategories);
        } 
        

        $programs = $query->get();
        return view('user_staff2.program-kerja.index', compact('programs'));
    }

    /**
     * Menampilkan formulir untuk membuat program kerja baru.
     */
    public function create()
    {
        // Kirim daftar kategori ke view untuk dropdown
        return view('user_staff2.program-kerja.create', ['categories' => $this->workCategories]);
    }

    /**
     * Menyimpan program kerja baru beserta tugas-tugasnya.
     */
    public function store(Request $request)
    {
        // === PERUBAHAN VALIDASI DI SINI ===
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tasks' => 'required|array|min:1',
            'tasks.*.description' => 'required|string|max:255', // Validasi field 'description' di dalam array tasks
        ], [
            // Tambahkan pesan custom jika perlu
             'tasks.*.description.required' => 'Deskripsi untuk setiap tugas wajib diisi.',
             'tasks.min' => 'Minimal harus ada satu tugas.'
        ]);

        DB::beginTransaction();
        try {
            $program = WorkProgram::create(['name' => $validated['name']]);

            $tasksData = [];
            // Loop melalui data tasks yang sudah divalidasi
            foreach ($validated['tasks'] as $taskItem) {
                if (!empty(trim($taskItem['description']))) {
                    $tasksData[] = ['description' => $taskItem['description']];
                }
            }

            if (!empty($tasksData)) {
                $program->tasks()->createMany($tasksData);
            } else {
                 DB::rollBack();
                 return back()->withInput()->withErrors(['tasks' => 'Minimal harus ada satu tugas yang valid.']);
            }

            DB::commit();
            return redirect()->route('staff.work-programs.index')->with('success', 'Program kerja berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan program kerja: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail program kerja dan tugas-tugasnya.
     */
public function show(WorkProgram $workProgram)
    {
        // Eager load tasks beserta relasi verifier-nya
        $workProgram->load(['tasks.verifier']);
        return view('user_staff2.program-kerja.show', compact('workProgram'));
    }

    /**
     * Menampilkan formulir untuk mengedit program kerja.
     */
    public function edit(WorkProgram $workProgram)
    {
        $workProgram->load('tasks');
        return view('user_staff2.program-kerja.edit', compact('workProgram'));
    }

    /**
     * Memperbarui program kerja dan tugas-tugasnya.
     */
    public function update(Request $request, WorkProgram $workProgram)
    {
         $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tasks' => 'required|array|min:1',
            'tasks.*.id' => 'nullable|exists:tasks,id', // Untuk tugas yang sudah ada
            'tasks.*.description' => 'required|string|max:255',
        ], [
             'tasks.*.description.required' => 'Deskripsi untuk setiap tugas wajib diisi.',
             'tasks.min' => 'Minimal harus ada satu tugas.'
        ]);

        DB::beginTransaction();
        try {
            $workProgram->update(['name' => $validated['name']]);

            $existingTaskIds = $workProgram->tasks()->pluck('id')->toArray();
            $newTaskIds = [];

            foreach ($validated['tasks'] as $taskData) {
                 if (!empty(trim($taskData['description']))) {
                    if (isset($taskData['id']) && in_array($taskData['id'], $existingTaskIds)) {
                        // Update tugas yang sudah ada
                        $task = Task::find($taskData['id']);
                        if ($task) {
                            $task->update(['description' => $taskData['description']]);
                            $newTaskIds[] = $task->id;
                        }
                    } else {
                        // Tambah tugas baru
                        $newTask = $workProgram->tasks()->create(['description' => $taskData['description']]);
                        $newTaskIds[] = $newTask->id;
                    }
                }
            }

            // Hapus tugas yang tidak ada di form (sudah dihapus user)
            $tasksToDelete = array_diff($existingTaskIds, $newTaskIds);
            if (!empty($tasksToDelete)) {
                Task::destroy($tasksToDelete);
            }

            DB::commit();
            return redirect()->route('staff.work-programs.show', $workProgram->id)->with('success', 'Program kerja berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui program kerja: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Menghapus program kerja beserta tugas-tugasnya.
     */
    public function destroy(WorkProgram $workProgram)
    {
        try {
            // onDelete('cascade') di migrasi akan otomatis menghapus tasks
            $workProgram->delete();
            return redirect()->route('staff.work-programs.index')->with('success', 'Program kerja berhasil dihapus.');
        } catch (\Exception $e) {
             return back()->with('error', 'Gagal menghapus program kerja: ' . $e->getMessage());
        }
    }

    /**
     * Staf mengajukan tugas untuk diverifikasi.
     */
    public function submitTaskForVerification(Request $request, Task $task)
    {
        $validated = $request->validate([
            'supporting_document_link' => 'required|url',
        ], [
            'supporting_document_link.required' => 'Link data dukung wajib diisi.',
            'supporting_document_link.url' => 'Input harus berupa URL yang valid.',
        ]);

        // Pastikan hanya tugas yang 'Belum Selesai' atau 'Revisi Diperlukan' yang bisa diajukan
        if (!in_array($task->status, ['Belum Selesai', 'Revisi Diperlukan'])) {
             return back()->with('error', 'Status tugas tidak valid untuk diajukan.');
        }

        $previousStatus = $task->status;
        $task->status = 'Menunggu Verifikasi';
        $task->supporting_document_link = $validated['supporting_document_link'];
        $task->verification_notes = null; // Hapus catatan lama jika ada
        $task->verifier_id = null; // Hapus verifier lama jika ada
        $task->save();

        // Catat Log (jika ada model log)
        // $this->logTaskStatusChange($task, Auth::id(), $previousStatus, $task->status, $validated['supporting_document_link']);

        return back()->with('success', 'Tugas berhasil diajukan untuk verifikasi.');
    }

    /**
     * Kanit melakukan verifikasi tugas.
     */
    public function verifyTask(Request $request, Task $task)
    {
        $user = Auth::user(); 

        
        // Pastikan user memiliki izin khusus untuk memverifikasi
        // Sesuaikan nama permission dengan kolom di DB Anda
        if (!$user || !$user->hasPermissionTo('Verifikasi Program Kerja')) { 
             return back()->with('error', 'Anda tidak memiliki izin untuk melakukan verifikasi.');
        }
        // === Akhir Perubahan ===

        $validated = $request->validate([
            'verification_status' => ['required', Rule::in(['Diverifikasi', 'Revisi Diperlukan'])],
            'verification_notes' => 'required_if:verification_status,Revisi Diperlukan|nullable|string',
        ], [
            'verification_notes.required_if' => 'Catatan wajib diisi jika meminta revisi.',
        ]);

        if ($task->status !== 'Menunggu Verifikasi') {
            return back()->with('error', 'Status tugas tidak valid untuk diverifikasi.');
        }

        $task->status = $validated['verification_status'];
        $task->verifier_id = $user->id;
        $task->verification_notes = $validated['verification_notes'];
        
        $task->save();

        return back()->with('success', 'Verifikasi tugas berhasil disimpan.');
    }

}
