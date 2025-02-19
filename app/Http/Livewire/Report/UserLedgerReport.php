<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ledger;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Session;

class UserLedgerReport extends Component
{
    use WithPagination;
    public $selected_customer;
    public $staff_id;
    public $active_details;
    public $searchResults = [];

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
        $this->selected_customer = '';
        $this->staff_id = '';
        $this->active_details = null;
    }

    public function customerDetails($id)
    {
        $this->active_details = $id;
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

    public function render()
    {
        $query = Ledger::query();

        if ($this->selected_customer) {
            $query->where('customer_id', $this->selected_customer);
        }

        if ($this->staff_id) {
            $query->where('staff_id', $this->staff_id);
        }

        $paymentData = $query->paginate(10);
        $staffs = User::where('user_type', 0)->get();

        return view('livewire.report.user-ledger-report', compact('paymentData', 'staffs'));
    }
}

