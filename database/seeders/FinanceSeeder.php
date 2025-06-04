<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Finance;
use App\Models\BudgetExpense;

class FinanceSeeder extends Seeder
{
    public function run()
    {
        // Pemasukan
        Finance::create(['date' => '2025-05-15', 'flow_type' => 'in', 'amount' => 52000000, 'note' => 'Pemasukan Mei 2025']);
        Finance::create(['date' => '2023-02-15', 'flow_type' => 'in', 'amount' => 48000000, 'note' => 'Pemasukan Februari 2023']);
        Finance::create(['date' => '2023-01-15', 'flow_type' => 'in', 'amount' => 50000000, 'note' => 'Pemasukan Januari 2023']);
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

        // Data untuk tahun 2023
        $finance3 = Finance::create(['date' => '2023-01-15', 'flow_type' => 'budget', 'amount' => 60000000, 'note' => 'Anggaran Januari 2023']);
        BudgetExpense::create(['finance_id' => $finance3->id, 'description' => 'Gaji Karyawan', 'amount' => 45000000]);

        $finance4 = Finance::create(['date' => '2023-02-15', 'flow_type' => 'budget', 'amount' => 58000000, 'note' => 'Anggaran Februari 2023']);
        BudgetExpense::create(['finance_id' => $finance4->id, 'description' => 'Operasional', 'amount' => 46000000]);

        // Tambahan data untuk tahun 2024
        // Pemasukan
        Finance::create([
            'date' => '2024-03-01',
            'flow_type' => 'in',
            'amount' => 1300000000,
            'note' => 'Pendapatan tiket pesawat Maret 2024',
        ]);

        Finance::create([
            'date' => '2024-06-01',
            'flow_type' => 'in',
            'amount' => 750000000,
            'note' => 'Pendapatan sewa toko bandara Juni 2024',
        ]);

        // Anggaran
        $finance2024_1 = Finance::create([
            'date' => '2024-04-10',
            'flow_type' => 'budget',
            'amount' => 1000000000,
            'note' => 'Anggaran operasional bandara April 2024',
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2024_1->id,
            'description' => 'Pemeliharaan Terminal',
            'amount' => 450000000,
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2024_1->id,
            'description' => 'Gaji Karyawan',
            'amount' => 350000000,
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2024_1->id,
            'description' => 'Utilitas',
            'amount' => 200000000,
        ]);

        $finance2024_2 = Finance::create([
            'date' => '2024-07-15',
            'flow_type' => 'budget',
            'amount' => 550000000,
            'note' => 'Anggaran keamanan Juli 2024',
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2024_2->id,
            'description' => 'Pembelian Scanner Keamanan',
            'amount' => 300000000,
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2024_2->id,
            'description' => 'Pelatihan Keamanan',
            'amount' => 250000000,
        ]);

        $finance2024_3 = Finance::create([
            'date' => '2024-10-05',
            'flow_type' => 'budget',
            'amount' => 65000000,
            'note' => 'Anggaran pemeliharaan Oktober 2024',
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2024_3->id,
            'description' => 'Pemeliharaan Sistem IT',
            'amount' => 50000000,
        ]);

        // Tambahan data untuk tahun 2022
        // Pemasukan
        Finance::create([
            'date' => '2022-02-01',
            'flow_type' => 'in',
            'amount' => 1100000000,
            'note' => 'Pendapatan tiket pesawat Februari 2022',
        ]);

        Finance::create([
            'date' => '2022-08-01',
            'flow_type' => 'in',
            'amount' => 600000000,
            'note' => 'Pendapatan parkir bandara Agustus 2022',
        ]);

        // Anggaran
        $finance2022_1 = Finance::create([
            'date' => '2022-03-10',
            'flow_type' => 'budget',
            'amount' => 900000000,
            'note' => 'Anggaran operasional bandara Maret 2022',
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2022_1->id,
            'description' => 'Pemeliharaan Apron',
            'amount' => 400000000,
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2022_1->id,
            'description' => 'Gaji Karyawan',
            'amount' => 300000000,
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2022_1->id,
            'description' => 'Utilitas',
            'amount' => 200000000,
        ]);

        $finance2022_2 = Finance::create([
            'date' => '2022-06-15',
            'flow_type' => 'budget',
            'amount' => 500000000,
            'note' => 'Anggaran keamanan Juni 2022',
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2022_2->id,
            'description' => 'CCTV Upgrade',
            'amount' => 280000000,
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2022_2->id,
            'description' => 'Pelatihan Petugas',
            'amount' => 220000000,
        ]);

        $finance2022_3 = Finance::create([
            'date' => '2022-11-05',
            'flow_type' => 'budget',
            'amount' => 55000000,
            'note' => 'Anggaran pemeliharaan November 2022',
        ]);
        BudgetExpense::create([
            'finance_id' => $finance2022_3->id,
            'description' => 'Pemeliharaan AC',
            'amount' => 40000000,
        ]);

        // Data untuk Mei 2025 (saat ini)
        $finance5 = Finance::create(['date' => '2025-05-15', 'flow_type' => 'budget', 'amount' => 62000000, 'note' => 'Anggaran Mei 2025']);
        BudgetExpense::create(['finance_id' => $finance5->id, 'description' => 'Pemeliharaan', 'amount' => 47000000]);
    }
}
