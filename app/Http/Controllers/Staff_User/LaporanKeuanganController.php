<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Finance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {

        // Ambil data dari database dan eager load data pengeluaran terkait anggaran (jika ada)
        $finances = Finance::with('budgetExpenses')->get();


        // Kembalikan ke view dengan data yang telah difilter
        return view('user_staff2.keuangan.index', compact('finances'));
    }


    public function create()
    {
        $finances = old('finance', []);

        

        return view('user_staff2.keuangan.create', [
            'finances' => $finances,
        ]);
    }

    public function edit($id)
    {

        $finance = Finance::with('budgetExpenses')->findOrFail($id);
        return view('user_staff2.keuangan.edit', compact('finance'));
    }


    public function store(Request $request)
    {
        // Ambil semua data finance yang masuk
        $finance = $request->input('finance');
        
        // Hitung total pengeluaran yang dimasukkan
        $budgetExpenses = $request->input('budget_expenses', []);
        $totalExpense = array_sum(array_column($budgetExpenses, 'amount'));

        // Ambil anggaran (amount) dari baris pertama finance
        $budgetAmount = $finance[0]['amount'] ?? 0;

        // Validasi jika total pengeluaran melebihi anggaran
        if ($totalExpense > $budgetAmount) {
            $errors = new MessageBag([
                'budget_expenses' => 'Total pengeluaran tidak boleh melebihi jumlah anggaran.',
            ]);

            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        // Validasi semua data dalam array 'finance'
        $validator = Validator::make($request->all(), [
            'finance' => 'required|array|min:1',
            'finance.*.flow_type' => 'required|in:in,budget',
            'finance.*.amount' => 'required|integer|min:1',
            'finance.*.date' => 'required|date_format:Y-m',
            'finance.*.note' => 'nullable|string',
            'budget_expenses' => 'nullable|array',
            'budget_expenses.*.description' => 'required|string',
            'budget_expenses.*.amount' => 'required|integer|min:1',
        ], [
            'finance.required' => 'Minimal satu baris data harus diisi.',
            'finance.*.flow_type.required' => 'Aliran dana wajib diisi.',
            'finance.*.amount.required' => 'Jumlah wajib diisi.',
            'finance.*.date.required' => 'Periode wajib diisi.',
            'finance.*.note.required' => 'Catatan wajib diisi.',
            'budget_expenses.*.description.required' => 'Deskripsi pengeluaran wajib diisi.',
            'budget_expenses.*.amount.required' => 'Jumlah pengeluaran wajib diisi.',
            'budget_expenses.*.amount.min' => 'Jumlah pengeluaran minimal adalah 1.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan data anggaran
        $financeRecord = Finance::create([
            'flow_type' => $finance[0]['flow_type'],
            'amount' => $finance[0]['amount'],
            'date' => $finance[0]['date'] . '-01', // Default tanggal agar valid sebagai `date`
            'note' => $finance[0]['note'] ?? null,
        ]);

        // Simpan data pengeluaran yang terkait dengan anggaran
        if ($totalExpense > 0) {
            foreach ($budgetExpenses as $expense) {
                $financeRecord->budgetExpenses()->create([
                    'description' => $expense['description'],
                    'amount' => $expense['amount'],
                ]);
            }
        }

        return redirect()->route('keuangan.staffIndex')->with('success', 'Data keuangan berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {

        $finance = Finance::findOrFail($id);
        $financeData = $request->input('finance');
        $budgetExpenses = $request->input('budget_expenses', []);
        $totalExpense = array_sum(array_column($budgetExpenses, 'amount'));
        $budgetAmount = $financeData[0]['amount'] ?? 0;

        if ($financeData[0]['flow_type'] === 'budget' && $totalExpense > $budgetAmount) {
            $errors = new MessageBag([
                'budget_expenses' => 'Total pengeluaran tidak boleh melebihi jumlah anggaran.',
            ]);
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $validator = Validator::make($request->all(), [
            'finance' => 'required|array|min:1',
            'finance.*.flow_type' => 'required|in:in,budget',
            'finance.*.amount' => 'required|integer|min:1',
            'finance.*.date' => 'required|date_format:Y-m',
            'finance.*.note' => 'nullable|string',
            'budget_expenses' => 'nullable|array',
            'budget_expenses.*.description' => 'required_if:finance.*.flow_type,budget|string',
            'budget_expenses.*.amount' => 'required_if:finance.*.flow_type,budget|integer|min:1',
        ], [
            'finance.required' => 'Minimal satu baris data harus diisi.',
            'finance.*.flow_type.required' => 'Aliran dana wajib diisi.',
            'finance.*.amount.required' => 'Jumlah wajib diisi.',
            'finance.*.date.required' => 'Periode wajib diisi.',
            'budget_expenses.*.description.required_if' => 'Deskripsi pengeluaran wajib diisi untuk anggaran.',
            'budget_expenses.*.amount.required_if' => 'Jumlah pengeluaran wajib diisi untuk anggaran.',
            'budget_expenses.*.amount.min' => 'Jumlah pengeluaran minimal adalah 1.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $finance->update([
            'flow_type' => $financeData[0]['flow_type'],
            'amount' => $financeData[0]['amount'],
            'date' => $financeData[0]['date'] . '-01',
            'note' => $financeData[0]['note'] ?? null,
        ]);

        if ($financeData[0]['flow_type'] === 'budget') {
            $finance->budgetExpenses()->delete();
            foreach ($budgetExpenses as $expense) {
                $finance->budgetExpenses()->create([
                    'description' => $expense['description'],
                    'amount' => $expense['amount'],
                ]);
            }
        } else {
            $finance->budgetExpenses()->delete();
        }

        return redirect()->route('keuangan.staffIndex')->with('success', 'Data keuangan berhasil diperbarui.');
    }

    public function destroy($id)
    {

        $finance = Finance::findOrFail($id);
        $finance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data keuangan berhasil dihapus.'
        ]);
    }



}
