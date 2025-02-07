<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class DepotExpanse extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;
    public $created_by;
    public $search = '';
    public $activeTab = 'dailyCollection';

    public function mount()
    {
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $query = Payment::with(['user', 'supplier'])->latest('updated_at');

        // Apply date filter
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('updated_at', [
                $this->start_date . ' 00:00:00',
                $this->end_date . ' 23:59:59'
            ]);
        }
        
        if (!empty($this->search)) {
            $query->whereHas('staff', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->orWhereHas('supplier', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        $totalAmount = $query->sum('amount');
        $payments = $query->paginate(10);
    
        return view('livewire.expense.depot-expanse', compact('payments','totalAmount'));
        
    }
}
