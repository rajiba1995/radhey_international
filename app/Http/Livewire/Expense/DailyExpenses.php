<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class DailyExpenses extends Component
{
    use WithFileUploads;

    public $expense_at = '';  // Stores whether "Stuff" or "Supplier" is selected
    public $stuff_id = '';    // Selected Stuff ID
    public $supplier_id = ''; // Selected Supplier ID
    public $expense_title = ''; // Selected Expense Title
    public $amount = ''; // Amount
    public $voucher_no = ''; // Voucher Number
    public $remarks = ''; // Remarks
    public $image; // File Upload for Image
    public $stuffOptions = [];    // Stores Stuff Names
    public $supplierOptions = []; // Stores Supplier Names
    public $stuffExpenseTitles = [];  // Holds Stuff Expense Titles
    public $supplierExpenseTitles = [];

    // Called when the 'expense_at' dropdown value changes
    public function onExpenseAtChange()
    {
        if ($this->expense_at == '1') {
            // Fetch Stuff list (user_type = 0 means stuff)
            $this->stuffOptions = User::where('user_type', 0)->pluck('name', 'id')->toArray();
            $this->supplierOptions = []; // Clear supplier options
            $this->supplier_id = null;   // Reset supplier selection
            $this->stuffExpenseTitles = Expense::where('for_staff', 1)->get(); 
            $this->supplierExpenseTitles = []; // Clear Supplier Titles
        } elseif ($this->expense_at == '2') {
            // Fetch Supplier list
            $this->supplierOptions = Supplier::pluck('name', 'id')->toArray();
            $this->stuffOptions = [];   // Clear stuff options
            $this->stuff_id = null;     // Reset stuff selection
            $this->supplierExpenseTitles = Expense::where('for_partner', 1)->get();
            $this->stuffExpenseTitles = [];
        } else {
            // If nothing is selected, reset both options
            $this->stuffOptions = [];
            $this->supplierOptions = [];
            $this->stuff_id = null;
            $this->supplier_id = null;
            $this->stuffExpenseTitles = [];
            $this->supplierExpenseTitles = [];
        }
    }
    public function mount()
    {
        // Auto-generate voucher number
        $this->generateVoucherNumber();
    }

    public function generateVoucherNumber()
    {
        // Get the latest voucher number
        $latestVoucher = Payment::latest()->first();

        // Extract the numeric part from the latest voucher number
        $lastVoucherNumber = $latestVoucher ? (int) substr($latestVoucher->voucher_no, 7) : 0;

        // Increment the voucher number by 1
        $newVoucherNumber = $lastVoucherNumber + 1;

        // Format the new voucher number as EXPENSE001, EXPENSE002, etc.
        $this->voucher_no = 'EXPENSE' . str_pad($newVoucherNumber, 3, '0', STR_PAD_LEFT);
    }
    public function submitForm()
    {
        $this->validate([
            'expense_at' => 'required',
            'expense_title' => 'required',
            'amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'image' => 'nullable|image|max:1024', // Validate the image upload
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('expenses', 'public');
        }

        // Create Payment Entry
        $payment = Payment::create([
            'stuff_id' => $this->expense_at == '1' ? $this->stuff_id : null,
            'supplier_id' => $this->expense_at == '2' ? $this->supplier_id : null,
            'expense_id' => $this->expense_title,
            'amount' => $this->amount,
            'voucher_no' => $this->voucher_no,
            'narration' => $this->remarks,
            'image' => $imagePath,
            'created_by' => Auth::id(),
        ]);

        session()->flash('message', 'Expense added successfully!');
        $this->reset(); // Reset the form fields
    }


    public function render()
    {
        return view('livewire.expense.daily-expenses');
    }
}
