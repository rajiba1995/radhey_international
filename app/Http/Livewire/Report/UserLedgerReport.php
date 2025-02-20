<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ledger;
use App\Models\User;
use App\Models\Payment;
use App\Models\Supplier;
use Illuminate\Support\Facades\Session;

class UserLedgerReport extends Component
{
    use WithPagination;
    public $selected_customer;
    public $staff_id;
    public $user_type;
    public $active_details;
    public $searchResults = [];
    public $customer_id;

    // public $customer_id;
    public $supplier_id;
    public $staffs = [];
    public $customers = [];
    public $suppliers = [];
    public $from_date,$to_date,$bank_cash;

    public $staffSearchTerm = '';
    public $staffSearchResults = [];

    public $customerSearchTerm = '';
    public $customerSearchResults = [];

    public $supplierSearchTerm = '';
    public $supplierSearchResults = [];

    
    public function updatingSelectedCustomer()
    {
        $this->resetPage();
    }

    public function FindCustomer($searchTerm)
    {
        $this->searchResults = User::where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('phone', 'like', '%' . $searchTerm . '%')
            ->take(10)
            ->get();
    }

    public function selectCustomer($customerId)
    {
        $this->selected_customer = $customerId;
        $this->searchResults = [];
    }

    public function resetForm()
    {
        $this->from_date = '';
        $this->to_date = '';
        $this->user_type = '';
        $this->user_id = '';
        $this->payment_type = '';
        $this->selected_customer = '';
        $this->staff_id = '';
    }

    // public function mount()
    // {
    //     $this->from_date = now()->startOfMonth()->toDateString(); // Default to first day of the month
    //     $this->to_date = now()->toDateString(); // Default to today
    //     $this->user_type = ''; // No user type selected by default
    //     $this->user_id = null; // No user selected by default
    //     $this->bank_cash = ''; // No payment type selected
    //     $this->customer_id = ''; // No payment type selected
    
    //     // Fetch initial dropdown data
    //     $this->staffs = User::where('user_type', 0)->get();
    //     $this->customers = User::where('user_type', 1)->get();
    //     $this->suppliers = Supplier::all();
    //     if ($this->customers->isNotEmpty()) {
    //         // $this->customer_id = $this->customers->first()->id;
    //         $this->customer_id= $this->customers->pluck('id')->toArray(); 
    //     }
    // }
    
    public function customerDetails($id)
    {
        $this->active_details = $id;
    }

    public function updatedUserType()
    {
        $this->user_id = null; // Reset user selection when user type changes
    }

    public function revokePayment($paymentId)
    {
        $payment = Payment::find($paymentId);
        if ($payment) {
            $payment->is_ledger_added = null;
            $payment->save();
            session()->flash('success', 'Payment revoked successfully.');
        }
    }
    public function searchStaff()
    {
        if (!empty($this->staffSearchTerm)) {
        // dd("hhdj");

            $this->staffSearchResults = User::where('user_type', 0) // 0 for staff
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->staffSearchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $this->staffSearchTerm . '%');
                })
                ->take(10)
                ->get();
        } else {
            $this->staffSearchResults = [];
        }
    }

    public function selectStaff($staffId)
    {
        $staff = User::find($staffId);
        if ($staff) {
            $this->staff_id = $staff->id;
            $this->staffSearchTerm = $staff->name; // Show selected staff name
        }
        $this->staffSearchResults = []; // Hide dropdown after selection
        $this->getUserLedger(); // Refresh ledger data
    }
    public function searchCustomer()
    {
        if (!empty($this->customerSearchTerm)) {
            $this->customerSearchResults = User::where('user_type', 1) // 1 for customers
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->customerSearchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $this->customerSearchTerm . '%');
                })
                ->take(10)
                ->get();
        } else {
            $this->customerSearchResults = [];
        }
    }

    public function selectCustomers($customerId)
    {
        $customer = User::find($customerId);
        if ($customer) {
            $this->customer_id = $customer->id;
            $this->customerSearchTerm = $customer->name; // Display selected name
        }
        $this->customerSearchResults = []; // Hide dropdown
        $this->getUserLedger(); // Refresh ledger data
    }

    public function searchSupplier()
    {
        if (!empty($this->supplierSearchTerm)) {
            $this->supplierSearchResults = Supplier::where('name', 'like', '%' . $this->supplierSearchTerm . '%')
                ->take(10)
                ->get();
        } else {
            $this->supplierSearchResults = [];
        }
    }

    public function selectSupplier($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if ($supplier) {
            $this->supplier_id = $supplier->id;
            $this->supplierSearchTerm = $supplier->name; // Display selected name
        }
        $this->supplierSearchResults = []; // Hide dropdown
        $this->getUserLedger(); // Refresh ledger data
    }
 
   
    public function getUser()
    {
        if ($this->user_type === 'staff') {
            $this->staffs = User::where('user_type', 0)->get();  // Fetch staff data
        } elseif ($this->user_type === 'customer') {
            $this->customers = User::where('user_type', 1)->get();   // Fetch customer data
        } elseif ($this->user_type === 'supplier') {
            $this->suppliers = Supplier::all();  // Fetch supplier data
        }
    }
    public function getUserLedger()
    {
        $query = Ledger::query();

        // Apply filters based on selected criteria
        if ($this->from_date) {
            $query->whereDate('entry_date', '>=', $this->from_date);
        }
        if ($this->to_date) {
            $query->whereDate('entry_date', '<=', $this->to_date);
        }
        if ($this->user_type) {
            if ($this->user_type === 'staff' && $this->staff_id) {
                $query->where('staff_id', $this->staff_id);
            } elseif ($this->user_type === 'customer' && $this->customer_id) {
                $query->where('customer_id', $this->customer_id);
            } elseif ($this->user_type === 'supplier' && $this->supplier_id) {
                $query->where('supplier_id', $this->supplier_id);
            }
        }
        if ($this->bank_cash) {
            $query->where('bank_cash', $this->bank_cash);
        }

        // Eager load related models and paginate
        $paymentData = $query->with(['staff', 'customer', 'supplier'])->orderBy('entry_date', 'desc')->paginate(10);

        return $paymentData;
    }
    public function render()
    {
        // Fetch user lists
        $staffs = User::where('user_type', 'staff')->get();
        $customers = User::where('user_type', 'customer')->get();
        $suppliers = Supplier::all();

        // Get the filtered ledger data
        $paymentData = $this->getUserLedger(); 

        return view('livewire.report.user-ledger-report', compact('paymentData', 'staffs', 'customers', 'suppliers'));
    }
}

