<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WorkProgram;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkProgramController extends Controller
{
    /**
     * Menampilkan daftar semua program kerja.
     */
    public function index()
    {
        // Eager load relasi tasks untuk menghitung progres
        $programs = WorkProgram::with('tasks')->latest()->get();
        return view('user_staff2.program-kerja.index', compact('programs'));
    }

    /**
     * Menampilkan formulir untuk membuat program kerja baru.
     */
    public function create()
    {
        return view('user_staff2.program-kerja.create');
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
        $workProgram->load('tasks'); // Load tugas
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
            'tasks' => 'nullable|array', // Tugas bisa jadi array kosong jika semua dihapus
            'tasks.*.id' => 'nullable|exists:tasks,id', // ID tugas yang sudah ada
            'tasks.*.description' => 'required|string|max:255',
            'tasks.*.is_completed' => 'sometimes|boolean', // Status selesai
        ]);

        DB::beginTransaction();
        try {
            // Update nama program kerja
            $workProgram->update(['name' => $validated['name']]);

            $existingTaskIds = [];
            $tasksToProcess = $validated['tasks'] ?? [];

            foreach ($tasksToProcess as $taskData) {
                 if (!empty(trim($taskData['description']))) {
                    if (isset($taskData['id'])) {
                        // Update tugas yang sudah ada
                        $task = Task::find($taskData['id']);
                        if ($task && $task->work_program_id === $workProgram->id) { // Pastikan task milik program ini
                            $task->update([
                                'description' => $taskData['description'],
                                'is_completed' => $taskData['is_completed'] ?? $task->is_completed ?? false,
                            ]);
                            $existingTaskIds[] = $task->id;
                        }
                    } else {
                        // Buat tugas baru
                        $newTask = $workProgram->tasks()->create([
                            'description' => $taskData['description'],
                            'is_completed' => $taskData['is_completed'] ?? false,
                        ]);
                         $existingTaskIds[] = $newTask->id;
                    }
                 }
            }

            // Hapus tugas yang tidak ada di request (sudah dihapus di form)
            $workProgram->tasks()->whereNotIn('id', $existingTaskIds)->delete();
            
             // Validasi ulang: pastikan masih ada minimal 1 tugas setelah update/delete
            if ($workProgram->tasks()->count() === 0 && count($tasksToProcess) > 0) {
                 DB::rollBack();
                 return back()->withInput()->withErrors(['tasks' => 'Program kerja harus memiliki minimal satu tugas yang valid.']);
            } elseif ($workProgram->tasks()->count() === 0 && count($tasksToProcess) == 0) {
                 // Jika memang sengaja dihapus semua, biarkan saja (atau beri validasi lain jika perlu)
            }


            DB::commit();
            return redirect()->route('staff.work-programs.index')->with('success', 'Program kerja berhasil diperbarui.');

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
     * Memperbarui status selesai/belum selesai suatu tugas via AJAX.
     */
    public function updateTaskStatus(Request $request, Task $task)
    {
        // Pastikan task milik program kerja yang benar (optional, tergantung route)
        // if ($task->work_program_id !== $workProgramId) { abort(403); }

        $validated = $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $task->update(['is_completed' => $validated['is_completed']]);

        // Hitung ulang progres program kerja induk
        $workProgram = $task->workProgram()->with('tasks')->first();
        $progress = $workProgram->progress_percentage; // Gunakan accessor

        return response()->json(['success' => true, 'progress' => $progress]);
    }
}
