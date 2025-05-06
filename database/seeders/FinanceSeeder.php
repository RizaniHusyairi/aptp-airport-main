<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Finance;
use App\Models\BudgetExpense;

class FinanceSeeder extends Seeder
{
    public function run()
    {
        //
        // Pemasukan
        Finance::create([
            'date' => '2025-05-01',
            'flow_type' => 'in',
            'amount' => 1500000000,
            'note' => 'Pendapatan dari penjualan tiket pesawat bulan Mei 2025',
        ]);

        Finance::create([
            'date' => '2025-05-02',
            'flow_type' => 'in',
            'amount' => 800000000,
            'note' => 'Pendapatan dari sewa ruang komersial di bandara',
        ]);

        // Anggaran dengan detail pengeluaran
        $finance1 = Finance::create([
            'date' => '2025-05-03',
            'flow_type' => 'budget',
            'amount' => 1200000000,
            'note' => 'Anggaran operasional bandara bulan Mei 2025',
        ]);

        BudgetExpense::create([
            'finance_id' => $finance1->id,
            'description' => 'Maintenance Landasan',
            'amount' => 500000000,
        ]);

        BudgetExpense::create([
            'finance_id' => $finance1->id,
            'description' => 'Gaji Karyawan',
            'amount' => 400000000,
        ]);

        BudgetExpense::create([
            'finance_id' => $finance1->id,
            'description' => 'Utilitas (Listrik, Air)',
            'amount' => 300000000,
        ]);

        $finance2 = Finance::create([
            'date' => '2025-05-04',
            'flow_type' => 'budget',
            'amount' => 600000000,
            'note' => 'Anggaran keamanan bandara bulan Mei 2025',
        ]);

        BudgetExpense::create([
            'finance_id' => $finance2->id,
            'description' => 'Peralatan Keamanan',
            'amount' => 350000000,
        ]);

        BudgetExpense::create([
            'finance_id' => $finance2->id,
            'description' => 'Pelatihan Petugas',
            'amount' => 250000000,
        ]);
    }
}
