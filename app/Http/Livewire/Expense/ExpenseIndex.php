<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;

class ExpenseIndex extends Component
{
    public $parent_id;

    public function saveExpense(){
        // dd($this->all());
    }
    public function mount($parent_id){
        $this->parent_id = $parent_id;
    }

    public function render()
    {
        return view('livewire.expense.expense-index');
    }   
}
