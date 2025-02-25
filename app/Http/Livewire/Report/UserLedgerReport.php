<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ledger;
use App\Models\User;
use App\Models\Payment;
use App\Models\Supplier;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LedgerExport; 
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class UserLedgerReport extends Component
{
    use WithPagination;
    public $selected_customer;
    public $staff_id;
    public $user_type;
    public $active_details;
    public $searchResults = [];
    public $customer_id;

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
    public $search;
    public $showList = false; 
    public $ledgerData = [];
    // public $type;
   
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

            $this->staffSearchResults = User::where('user_type', 0) // 0 for staff
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->staffSearchTerm . '%');
                })
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
                    $query->where('name', 'like', '%' . $this->customerSearchTerm . '%');
                })
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
    // public function getUserLedger()
    // {
    //     $this->validate([
    //         'from_date' => 'required|date',
    //         'to_date' => 'required|date|after_or_equal:from_date',
    //     ]);
    //     $query = Ledger::query();

    //     // Apply filters based on selected criteria
    //     if ($this->from_date) {
    //         $query->whereDate('entry_date', '>=', $this->from_date);
    //     }
    //     if ($this->to_date) {
    //         $query->whereDate('entry_date', '<=', $this->to_date);
    //     }
    //     if ($this->user_type) {
    //         if ($this->user_type === 'staff' && $this->staff_id) {
    //             $query->where('staff_id', $this->staff_id);
    //         } elseif ($this->user_type === 'customer' && $this->customer_id) {
    //             $query->where('customer_id', $this->customer_id);
    //         } elseif ($this->user_type === 'supplier' && $this->supplier_id) {
    //             $query->where('supplier_id', $this->supplier_id);
    //         }
    //     }
    //     if ($this->bank_cash) {
    //         $query->where('bank_cash', $this->bank_cash);
    //     }

    //     // Eager load related models and paginate
    //     $paymentData = $query->with(['staff', 'customer', 'supplier'])->orderBy('entry_date', 'desc')->paginate(10);
    //     $this->showList = true;
    //     return $paymentData;
    // }
    public function getUserLedger()
    {
        $query = Ledger::query();

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

        // Fetch data and update visibility
        $this->ledgerData = $query->get(); // Store data in property
        $this->showList = true;
    }
    public function generatePDF()
    {
        $selectUserName = 'All'; // Default value

        if ($this->user_type === 'staff' && $this->staff_id) {
            $staff = User::find($this->staff_id);
            $selectUserName = $staff ? $staff->name : 'Unknown Staff';
        } elseif ($this->user_type === 'customer' && $this->customer_id) {
            $customer = User::find($this->customer_id);
            $selectUserName = $customer ? $customer->name : 'Unknown Customer';
        } elseif ($this->user_type === 'supplier' && $this->supplier_id) {
            $supplier = Supplier::find($this->supplier_id);
            $selectUserName = $supplier ? $supplier->name : 'Unknown Supplier';
        }
        $data = [
            'user_type' => $this->user_type,
            'select_user_name' => $selectUserName,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'data' => $this->ledgerData,
            'day_opening_amount' => 0,
            'is_opening_bal_showable' => true,
        ];

        // dd($data);
        $pdf = Pdf::loadView('ledger.pdf', $data)->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'ledger_report.pdf');
    }


    public function render()
    {
        $staffs = User::where('user_type', 'staff')->get();
        $customers = User::where('user_type', 'customer')->get();
        $suppliers = Supplier::all();

        return view('livewire.report.user-ledger-report', [
            'staffs' => $staffs,
            'customers' => $customers,
            'suppliers' => $suppliers,
            'ledgerData' => $this->ledgerData // Use the property
        ]);
    }

    
    public function exportLedger()
    {
        // Call the LedgerExport with dynamic filters
        return Excel::download(
            new LedgerExport(
                $this->from_date, 
                $this->to_date, 
                $this->user_type, 
                $this->staff_id, 
                $this->customer_id, 
                $this->supplier_id, 
                $this->bank_cash, 
                $this->search
            ),
            'ledger.xlsx'
        );
    }

    // public function generatePDF(Request $request)
    // {
    //     $data = [
    //         'user_type' => $request->user_type,
    //         'select_user_name' => $request->select_user_name,
    //         'from_date' => $request->from_date,
    //         'to_date' => $request->to_date,
    //         'data' => $this->ledgerData, // Use existing property instead of calling method
    //         'day_opening_amount' => 0, // Adjust as per your logic
    //         'is_opening_bal_showable' => true,
    //     ];
    
    //     $pdf = Pdf::loadView('ledger.pdf', $data);
    //     return $pdf->download('ledger_report.pdf');
    // }
    // public function generatePDF(Request $request)
    //     {
    //         $data = [
    //             'user_type' => utf8_encode($request->user_type),
    //             'select_user_name' => utf8_encode($request->select_user_name),
    //             'from_date' => $request->from_date,
    //             'to_date' => $request->to_date,
    //             'data' => $this->getUserLedger(),
    //             'day_opening_amount' => 0,
    //             'is_opening_bal_showable' => true,
    //         ];

    //         $pdf = Pdf::loadView('ledger.pdf', $data, [], [
    //             'mode' => 'utf-8',
    //             'default_font' => 'sans-serif' // Use a UTF-8 compatible font
    //         ]);

    //         // return $pdf->download('ledger_report.pdf');
    //     }

    
}

