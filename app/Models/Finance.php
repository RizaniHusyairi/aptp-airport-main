<?php

namespace App\Models;
use App\Models\BudgetExpense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'flow_type',
        'amount',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function budgetExpenses(): HasMany
    {
        return $this->hasMany(BudgetExpense::class, 'finance_id');
    }


    /**
     * Cek apakah ini adalah anggaran
     */
    public function isBudget()
    {
        return $this->flow_type === 'budget';
    }
    
    /**
     * Cek apakah ini adalah pemasukan
     */
    public function isIncome()
    {
        return $this->flow_type === 'in';
    }
    
    /**
     * Mendapatkan total pengeluaran dari anggaran ini
     */
    public function getTotalExpenses()
    {
        return $this->budgetExpenses()->sum('amount');
    }
    
    /**
     * Mendapatkan sisa anggaran
     */
    public function getRemainingBudget()
    {
        if (!$this->isBudget()) {
            return 0;
        }
        
        return $this->amount - $this->getTotalExpenses();
    }
}
