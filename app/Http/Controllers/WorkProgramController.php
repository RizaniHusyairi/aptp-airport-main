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
    // Daftar semua kategori master (untuk Admin/Superuser)
    private $allCategories = [
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
     * Helper: Mendapatkan kategori yang diizinkan untuk User yang sedang login.
     */
    private function getAllowedCategories($user)
    {
        // 1. Jika Admin, kembalikan semua kategori
        if ($user->is_admin) {
            return $this->allCategories;
        }

        // 2. Ambil kategori dari Role yang dimiliki user
        $allowedCategories = [];
        
        // Kita load role beserta workCategories-nya untuk efisiensi
        // Asumsi: Anda belum membuat relasi hasMany di model Role ke RoleWorkCategory, 
        // jadi kita query manual dulu seperti di kode lama Anda.
        foreach ($user->roles as $role) {
            $cats = RoleWorkCategory::where('role_id', $role->id)
                                    ->pluck('category_name')
                                    ->toArray();
            $allowedCategories = array_merge($allowedCategories, $cats);
        }

        // Hapus duplikat
        return array_unique($allowedCategories);
    }

    /**
     * Menampilkan daftar program kerja (Difilter sesuai hak akses).
     */
    public function index()
    {
        $user = Auth::user();
        $query = WorkProgram::with(['tasks', 'creator'])->latest(); // Eager load creator jika ada relasinya

        $allowedCategories = $this->getAllowedCategories($user);

        // Jika user bukan admin dan punya batasan kategori
        if (!$user->is_admin) {
            if (!empty($allowedCategories)) {
                $query->whereIn('category', $allowedCategories);
            } else {
                // Jika tidak punya kategori sama sekali, jangan tampilkan apa-apa
                // Kecuali mungkin dia Staff Administrasi umum (opsional, sesuaikan kebijakan)
                $query->whereRaw('1 = 0'); 
            }
        }

        $programs = $query->get();
        return view('user_staff2.program-kerja.index', compact('programs'));
    }

    /**
     * Menampilkan form tambah. Dropdown kategori difilter.
     */
    public function create()
    {
        $user = Auth::user();
        $allowedCategories = $this->getAllowedCategories($user);

        // Jika user tidak punya akses ke kategori apapun
        if (empty($allowedCategories) && !$user->is_admin) {
            return redirect()->route('staff.work-programs.index')
                ->with('error', 'Anda tidak memiliki akses kategori untuk membuat program kerja. Hubungi Admin.');
        }

        return view('user_staff2.program-kerja.create', ['categories' => $allowedCategories]);
    }

    /**
     * Menyimpan data. Validasi kategori wajib dilakukan di sini (Backend Validation).
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $allowedCategories = $this->getAllowedCategories($user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => ['required', 'string', Rule::in($allowedCategories)],
            'tasks' => 'required|array|min:1',
            'tasks.*.description' => 'required|string|max:255',
        ], [
            'category.in' => 'Anda tidak memiliki hak akses untuk membuat program kerja di kategori ini.',
            'tasks.*.description.required' => 'Deskripsi tugas wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $program = WorkProgram::create([
                'name' => $validated['name'],
                'category' => $validated['category'],
                'user_id' => Auth::id(), // SIMPAN ID PEMBUAT DI SINI
            ]);

            $tasksData = [];
            foreach ($validated['tasks'] as $taskItem) {
                if (!empty(trim($taskItem['description']))) {
                    $tasksData[] = ['description' => $taskItem['description']];
                }
            }

            if (!empty($tasksData)) {
                $program->tasks()->createMany($tasksData);
            } else {
                DB::rollBack();
                return back()->withInput()->withErrors(['tasks' => 'Minimal harus ada satu tugas valid.']);
            }

            DB::commit();
            return redirect()->route('staff.work-programs.index')->with('success', 'Program kerja berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail. Pastikan user berhak melihat detail ini.
     */
    public function show(WorkProgram $workProgram)
    {
        $user = Auth::user();
        $allowedCategories = $this->getAllowedCategories($user);

        // Security Check: Apakah user punya akses ke kategori program ini?
        if (!$user->is_admin && !in_array($workProgram->category, $allowedCategories)) {
            abort(403, 'Anda tidak memiliki akses ke kategori program kerja ini.');
        }

        $workProgram->load(['tasks.verifier']);
        return view('user_staff2.program-kerja.show', compact('workProgram'));
    }

    /**
     * Form edit. Filter kategori jika ingin diubah.
     */
    public function edit(WorkProgram $workProgram)
    {
        $user = Auth::user();
        $allowedCategories = $this->getAllowedCategories($user);

        // Security Check
        if (!$user->is_admin && !in_array($workProgram->category, $allowedCategories)) {
            abort(403, 'Anda tidak berhak mengedit program kerja ini.');
        }

        $workProgram->load('tasks');
        return view('user_staff2.program-kerja.edit', [
            'workProgram' => $workProgram,
            'categories' => $allowedCategories // Kirim hanya kategori yang diizinkan
        ]);
    }

    /**
     * Update data. Validasi kategori lagi.
     */
    public function update(Request $request, WorkProgram $workProgram)
    {
        $user = Auth::user();
        $allowedCategories = $this->getAllowedCategories($user);

        // Security Check Awal
        if (!$user->is_admin && !in_array($workProgram->category, $allowedCategories)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => ['required', 'string', Rule::in($allowedCategories)], // Validasi kategori baru
            'tasks' => 'required|array|min:1',
            'tasks.*.id' => 'nullable|exists:tasks,id',
            'tasks.*.description' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $workProgram->update([
                'name' => $validated['name'],
                'category' => $validated['category']
            ]);

            // ... (Logika update tasks sama seperti sebelumnya) ...
            $existingTaskIds = $workProgram->tasks()->pluck('id')->toArray();
            $newTaskIds = [];

            foreach ($validated['tasks'] as $taskData) {
                if (!empty(trim($taskData['description']))) {
                    if (isset($taskData['id']) && in_array($taskData['id'], $existingTaskIds)) {
                        $task = Task::find($taskData['id']);
                        if ($task) {
                            $task->update(['description' => $taskData['description']]);
                            $newTaskIds[] = $task->id;
                        }
                    } else {
                        $newTask = $workProgram->tasks()->create(['description' => $taskData['description']]);
                        $newTaskIds[] = $newTask->id;
                    }
                }
            }

            $tasksToDelete = array_diff($existingTaskIds, $newTaskIds);
            if (!empty($tasksToDelete)) {
                Task::destroy($tasksToDelete);
            }

            DB::commit();
            return redirect()->route('staff.work-programs.show', $workProgram->id)->with('success', 'Program kerja diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy(WorkProgram $workProgram)
    {
        $user = Auth::user();
        $allowedCategories = $this->getAllowedCategories($user);

        if (!$user->is_admin && !in_array($workProgram->category, $allowedCategories)) {
            abort(403, 'Akses ditolak.');
        }

        try {
            $workProgram->delete();
            return redirect()->route('staff.work-programs.index')->with('success', 'Program kerja dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    // ... method submitTaskForVerification dan verifyTask biarkan tetap seperti kode sebelumnya (sudah aman) ...
    public function submitTaskForVerification(Request $request, Task $task)
    {
        $validated = $request->validate([
            'supporting_document_link' => 'required|url',
        ], [
            'supporting_document_link.required' => 'Link data dukung wajib diisi.',
            'supporting_document_link.url' => 'Input harus berupa URL yang valid.',
        ]);

        if (!in_array($task->status, ['Belum Selesai', 'Revisi Diperlukan'])) {
             return back()->with('error', 'Status tugas tidak valid untuk diajukan.');
        }

        $task->status = 'Menunggu Verifikasi';
        $task->supporting_document_link = $validated['supporting_document_link'];
        $task->verification_notes = null; 
        $task->verifier_id = null; 
        $task->save();

        return back()->with('success', 'Tugas berhasil diajukan untuk verifikasi.');
    }

    public function verifyTask(Request $request, Task $task)
    {
        $user = Auth::user(); 

        if (!$user || !$user->hasPermission('Verifikasi Program Kerja')) { 
             return back()->with('error', 'Anda tidak memiliki izin untuk melakukan verifikasi.');
        }

        // TAMBAHAN KEAMANAN: Pastikan Verifikator juga punya hak atas kategori tugas ini
        // Misal: Kanit Teknik tidak boleh verifikasi tugas kategori 'Keuangan'
        $task->load('workProgram');
        $allowedCategories = $this->getAllowedCategories($user);
        if (!$user->is_admin && !in_array($task->workProgram->category, $allowedCategories)) {
             return back()->with('error', 'Anda tidak berhak memverifikasi kategori tugas ini.');
        }

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