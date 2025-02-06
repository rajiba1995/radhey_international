<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;

class DepotExpanse extends Component
{
    
    use WithPagination;

    public $payment_for, $payment_in, $bank_cash, $voucher_no, $payment_date, 
           $payment_mode, $amount, $chq_utr_no, $bank_name, $narration, $is_gst;

    public $expanse_id;
    public $updateMode = false;

    protected $rules = [
        'payment_for' => 'required|string|max:255',
        'payment_in' => 'required|string|max:255',
        'bank_cash' => 'required|in:bank,cash',
        'voucher_no' => 'required|unique:depot_expanses,voucher_no',
        'payment_date' => 'required|date',
        'payment_mode' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'chq_utr_no' => 'nullable|string|max:255',
        'bank_name' => 'nullable|string|max:255',
        'narration' => 'nullable|string',
        'is_gst' => 'boolean',
    ];

    public function render()
    {
        return view('livewire.expense.depot-expanse', [
            // 'expanses' => DepotExpanse::latest()->paginate(10),
            'expanses' => Payment::latest()->paginate(10),
        ]);
    }

    public function store()
    {
        $this->validate();

        Payment::create([
            'payment_for' => $this->payment_for,
            'payment_in' => $this->payment_in,
            'bank_cash' => $this->bank_cash,
            'voucher_no' => $this->voucher_no,
            'payment_date' => $this->payment_date,
            'payment_mode' => $this->payment_mode,
            'amount' => $this->amount,
            'chq_utr_no' => $this->chq_utr_no,
            'bank_name' => $this->bank_name,
            'narration' => $this->narration,
            'is_gst' => $this->is_gst,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Depot Expense Created Successfully.');

        $this->reset();
    }
}
