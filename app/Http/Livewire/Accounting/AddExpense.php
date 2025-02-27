<?php

namespace App\Http\Livewire\Accounting;

use Livewire\Component;
use App\Models\Ledger;
use App\Models\User;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\Expense;
use App\Models\Journal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AddExpense extends Component
{
    public $user_id, $user_name, $expense_id, $voucher_no, $payment_date, $payment_mode, $amount, $user_type, $receipt_for = "Customer";
    public $bank_name, $chq_utr_no, $narration;
    public $activePayementMode = 'cash';
    public $readonly = "readonly";
    public $staffSearchTerm = '', $customerSearchTerm = '', $supplierSearchTerm = '';
    public $staffSearchResults = [], $customerSearchResults = [], $supplierSearchResults = [];
    public $customer_id, $supplier_id, $staff_id, $customer;
    public $errorMessage = [];
    public $stuffOptions = [];
    public $supplierOptions = [];
    public $stuff_id = null;
    public $stuffExpenseTitles = [];
    public $supplierExpenseTitles = [];
    public $customerExpenseTitles = [];

    public function getUser($value){
        $this->user_type = $value;
        $this->reset(['customerSearchTerm','supplierSearchTerm','staffSearchTerm']);
        if ($this->user_type == 'staff') {
            // Fetch Stuff list (user_type = 0 means stuff)
            $this->stuffOptions = User::where('user_type', 0)->pluck('name', 'id')->toArray();
            $this->supplierOptions = []; // Clear supplier options
            $this->supplier_id = null;   // Reset supplier selection
            $this->stuffExpenseTitles = Expense::where('for_staff', 1)->where('for_debit',1)->get(); 
            $this->supplierExpenseTitles = []; // Clear Supplier Titles
        } elseif ($this->user_type == 'supplier') {
            // Fetch Supplier list
            $this->supplierOptions = Supplier::pluck('name', 'id')->toArray();
            $this->stuffOptions = [];   // Clear stuff options
            $this->stuff_id = null;     // Reset stuff selection
            $this->supplierExpenseTitles = Expense::where('for_partner', 1)->where('for_debit',1)->get();
            $this->stuffExpenseTitles = [];
        }   elseif ($this->user_type == 'customer') {
            // Fetch Supplier list 
            $this->supplierOptions = Supplier::pluck('name', 'id')->toArray();
            $this->stuffOptions = [];   // Clear stuff options
            $this->stuff_id = null;     // Reset stuff selection
            $this->supplierExpenseTitles = [];
            $this->customerExpenseTitles = Expense::where('for_store', 1)->where('for_debit',1)->get();
        } 
        else {
            // If nothing is selected, reset both options
            $this->stuffOptions = [];
            $this->supplierOptions = [];
            $this->stuff_id = null;
            $this->supplier_id = null;
            $this->stuffExpenseTitles = [];
            $this->supplierExpenseTitles = [];
            $this->customerExpenseTitles = [];
        }
    }
    public function mount(){
        $this->voucher_no = 'EXPENSE'.time();
      
    }
    public function searchStaff()
    {
        if (!empty($this->staffSearchTerm)) {

            $this->staffSearchResults = User::where('user_type', 0) // 0 for staff
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->staffSearchTerm . '%');
                })
                ->get();
        } else {
            $this->staffSearchResults = [];
        }
    }

    public function searchCustomer()
    {
        if (!empty($this->customerSearchTerm)) {
            $this->customerSearchResults = User::where('user_type', 1) // 1 for customers
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->customerSearchTerm . '%');
                })
                ->get();
        } else {
            $this->customerSearchResults = [];
        }
    }
    public function searchSupplier()
    {
        if (!empty($this->supplierSearchTerm)) {
            $this->supplierSearchResults = Supplier::where('name', 'like', '%' . $this->supplierSearchTerm . '%')
                ->get();
        } else {
            $this->supplierSearchResults = [];
        }
    }
    public function selectStaff($staffId)
    {
        $staff = User::find($staffId);
        if ($staff) {
            $this->staff_id = $staff->id;
            $this->staffSearchTerm = $staff->name; // Show selected staff name
            $this->reset(['customer_id','supplier_id']);
        }
        $this->staffSearchResults = []; // Hide dropdown after selection
        $this->reset(['errorMessage']);
    }
    public function selectCustomers($customerId)
    {
        $customer = User::find($customerId);
        if ($customer) {
            $this->customer_id = $customer->id;
            $this->customerSearchTerm = $customer->name; // Display selected name
            $this->reset(['staff_id','supplier_id']);
        }
        $this->customerSearchResults = []; // Hide dropdown
        $this->reset(['errorMessage']);
    }
    public function selectSupplier($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if ($supplier) {
            $this->supplier_id = $supplier->id;
            $this->supplierSearchTerm = $supplier->name; // Display selected name
            $this->reset(['customer_id','staff_id']);
            
        }
        $this->reset(['errorMessage']);
        $this->supplierSearchResults = []; // Hide dropdown
    }
    
    public function ChangePaymentMode($value){
        $this->activePayementMode = $value;
    }

    // public function saveExpenses()
    // {
    //     // dd("juj");
    //     $this->validate([
    //         // 'payment_date' => 'required', 
    //         'payment_mode' => 'required', 
    //         // 'amount' => 'required|numeric', 
    //         // 'user_type' => 'required',
    //         // 'user_id' => 'rename' => 'required',
    //         // 'expense_quired',
    //         // 'user_id' => 'required'
    //     // ], [
    //         // 'payment_date.required' => "Please add date of payment",
    //         // 'payment_mode.required' => "Please mention mode of payment",
    //         // 'amount.required' => "Please add amount",
    //         // 'amount.numeric' => "Amount must be a number",
    //         // 'user_type.required' => "Please mention expense at",
    //         // // 'user_id.required' => "Please specify which user",
    //         // 'expense_id.required' => "Please add expense type"
    //     ]);
    //     $userId = $this->user_type === 'staff' ? $this->staff_id :
    //     ($this->user_type === 'customer' ? $this->customer_id :
    //     ($this->user_type === 'supplier' ? $this->supplier_id : null));
    //     // dd($userId);
    //     if (!$userId) {
    //     $this->errorMessage['user'] = 'Please select a valid user.';
    //     return;
    //     }

    //     $paymentData = [
    //         'payment_for' => 'debit',
    //         'voucher_no' => $this->voucher_no,
    //         'payment_date' => $this->payment_date,
    //         'payment_mode' => $this->payment_mode,
    //         'payment_in' => ($this->payment_mode != 'cash') ? 'bank' : 'cash',
    //         'bank_cash' => ($this->payment_mode == 'cash') ? 'cash' : 'bank',
    //         'amount' => $this->amount,
    //         'bank_name' => $this->bank_name,
    //         'chq_utr_no' => $this->chq_utr_no,
    //         'narration' => $this->narration,
    //         'created_by' => Auth::user()->id,
    //         'created_at' => now()
    //     ];

    //     if ($this->user_type == 'staff') {
    //         $paymentData['staff_id'] = $this->staff_id;
    //     } elseif ($this->user_type == 'supplier') {
    //         $paymentData['supplier_id'] = $this->supplier_id;
    //     } elseif ($this->user_type == 'customer') {
    //         $paymentData['customer_id'] = $this->customer_id;
    //     }

    //     if (!empty($this->expense_id)) {
    //         $paymentData['expense_id'] = $this->expense_id;
    //     }

    //     $payment_id = Payment::insertGetId($paymentData);

    //     $ledgerData = [
    //         'user_type' => $this->user_type,
    //         'transaction_id' => $this->voucher_no,
    //         'transaction_amount' => $this->amount,
    //         'payment_id' => $payment_id,
    //         'bank_cash' => ($this->payment_mode == 'cash') ? 'cash' : 'bank',
    //         'is_credit' => 0,
    //         'is_debit' => 1,
    //         'entry_date' => $this->payment_date,
    //         'purpose' => 'expense',
    //         'purpose_description' => "Expense for " . $this->user_type,
    //         'created_at' => now()
    //     ];

    //     Ledger::insert($ledgerData);

    //     Journal::insert([
    //         'transaction_amount' => $this->amount,
    //         'is_credit' => 0,
    //         'is_debit' => 1,
    //         'entry_date' => $this->payment_date,
    //         'payment_id' => $payment_id,
    //         'bank_cash' => ($this->payment_mode == 'cash') ? 'cash' : 'bank',
    //         'purpose' => 'expense',
    //         'purpose_description' => "Journal Entry for expense",
    //         'purpose_id' => $this->voucher_no,
    //         'created_at' => now()
    //     ]);
    //     Expense::create([
    //         'user_id' => $userId,
    //         'amount' => $this->amount,
    //         'voucher_no' => $this->voucher_no,
    //         'payment_date' => $this->payment_date,
    //         'payment_mode' => $this->payment_mode,
    //         'chq_utr_no' => $this->chq_utr_no,
    //         'bank_name' => $this->bank_name,
    //         'receipt_for' => $this->receipt_for,
    //     ]);
    //     Session::flash('message', "Expense added successfully for " . $this->user_type);
    //     return redirect()->route('admin.accounting.add_expenses');
    // }
    public function saveExpenses()
    {
        // Validate input data
        $this->validate([
            'payment_mode' => 'required',
            // Add additional validation rules here
        ]);

        // Determine the user ID based on user type
        $userId = $this->user_type === 'staff' ? $this->staff_id :
            ($this->user_type === 'customer' ? $this->customer_id :
            ($this->user_type === 'supplier' ? $this->supplier_id : null));

        if (!$userId) {
            $this->errorMessage['user'] = 'Please select a valid user.';
            return;
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Prepare payment data
            $paymentData = [
                'payment_for' => 'debit',
                'voucher_no' => $this->voucher_no,
                'payment_date' => $this->payment_date,
                'payment_mode' => $this->payment_mode,
                'payment_in' => ($this->payment_mode != 'cash') ? 'bank' : 'cash',
                'bank_cash' => ($this->payment_mode == 'cash') ? 'cash' : 'bank',
                'amount' => $this->amount,
                'bank_name' => $this->bank_name,
                'chq_utr_no' => $this->chq_utr_no,
                'narration' => $this->narration,
                'created_by' => Auth::user()->id,
                'created_at' => now()
            ];

            // Conditionally add the appropriate user ID to the payment data
            if ($this->user_type == 'staff') {
                $paymentData['stuff_id'] = $this->staff_id; // Keep stuff_id as it is in the table
            } elseif ($this->user_type == 'supplier') {
                $paymentData['supplier_id'] = $this->supplier_id;
            } elseif ($this->user_type == 'customer') {
                $paymentData['customer_id'] = $this->customer_id;
            }

            // Insert payment data and get the inserted ID
            $payment_id = Payment::insertGetId($paymentData);

            // Prepare ledger data
            $ledgerData = [
                'user_type' => $this->user_type,
                'transaction_id' => $this->voucher_no,
                'transaction_amount' => $this->amount,
                'payment_id' => $payment_id,
                'bank_cash' => ($this->payment_mode == 'cash') ? 'cash' : 'bank',
                'is_credit' => 0,
                'is_debit' => 1,
                'entry_date' => $this->payment_date,
                'purpose' => 'expense',
                'purpose_description' => "Expense for " . $this->user_type,
                'created_at' => now()
            ];

            // Insert ledger data
            Ledger::insert($ledgerData);

            // Prepare journal entry data
            Journal::insert([
                'transaction_amount' => $this->amount,
                'is_credit' => 0,
                'is_debit' => 1,
                'entry_date' => $this->payment_date,
                'payment_id' => $payment_id,
                'bank_cash' => ($this->payment_mode == 'cash') ? 'cash' : 'bank',
                'purpose' => 'expense',
                'purpose_description' => "Journal Entry for expense",
                'purpose_id' => $this->voucher_no,
                'created_at' => now()
            ]);

            // Create expense record
            Expense::create([
                'user_id' => $userId,
                'amount' => $this->amount,
                'voucher_no' => $this->voucher_no,
                'payment_date' => $this->payment_date,
                'payment_mode' => $this->payment_mode,
                'chq_utr_no' => $this->chq_utr_no,
                'bank_name' => $this->bank_name,
                'receipt_for' => $this->receipt_for,
            ]);

            // Commit the transaction
            DB::commit();

            // Flash success message and redirect
            Session::flash('message', "Expense added successfully for " . $this->user_type);
            return redirect()->route('admin.accounting.add_depot_expense');
        } catch (\Exception $e) {
            dd($e);
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Handle error, log, or display the message
            Session::flash('error', 'Something went wrong: ' . $e->getMessage());
            return redirect()->route('admin.accounting.add_depot_expense');
        }
    }

    public function render()
    {
        return view('livewire.accounting.add-expense');
    }
}
