<?php

namespace App\Http\Livewire\Accounting;

use Livewire\Component;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddPaymentReceipt extends Component
{   
    public $searchResults = [];
    public $errorMessage = [];
    public $activePayementMode = 'cash';
    public $staffs =[];
    public $customer,$customer_id, $staff_id, $amount, $voucher_no, $payment_date, $payment_mode, $chq_utr_no, $bank_name;

    public function mount(){
        $this->voucher_no = 'PAYRECEIPT'.time();
        $this->staffs = User::where('user_type', 0)->where('designation', 2)->select('name', 'id')->orderBy('name', 'ASC')->get();
    }

   
    public function submitForm()
    {
        $this->reset(['errorMessage']);
        $this->errorMessage = array();
        // Validate customer
        if (empty($this->customer_id)) {
           $this->errorMessage['customer_id'] = 'Please select a customer.';
        }

        // Validate collected by
        if (empty($this->staff_id)) {
           $this->errorMessage['staff_id'] = 'Please select a staff member.';
        }

        // Validate amount
        if (empty($this->amount) || !is_numeric($this->amount)) {
           $this->errorMessage['amount'] = 'Please enter a valid amount.';
        }

        // Validate voucher no
        if (empty($this->voucher_no)) {
           $this->errorMessage['voucher_no'] = 'Please enter a voucher number.';
        }

        // Validate payment date
        if (empty($this->payment_date) || !$this->is_valid_date($this->payment_date)) {
           $this->errorMessage['payment_date'] = 'Please select a valid payment date.';
        }

        // Validate payment mode
        if (empty($this->payment_mode)) {
           $this->errorMessage['payment_mode'] = 'Please select a payment mode.';
        }

        // Validate cheque no / UTR no
        if ($this->payment_mode != 'cash' && empty($this->chq_utr_no)) {
           $this->errorMessage['chq_utr_no'] = 'Please enter a cheque no / UTR no.';
        }

        // Validate bank name
        if ($this->payment_mode != 'cash' && empty($this->bank_name)) {
           $this->errorMessage['bank_name'] = 'Please enter a bank name.';
        }
        if(count($this->errorMessage)>0){
            return $this->errorMessage;
        }else{

            try {
                DB::beginTransaction();
                //code...
            } catch (\Exception $e) {
                DB::rollBack();
                Session::flash('message', $e->getMessage());
                \Log::error('Error in invoicePayments: ' . $e->getMessage());
            }
            dd($this->all());
        }
       
    }

    public function FindCustomer($term)
    {
        $this->searchTerm = $term;

        if (!empty($this->searchTerm)) {
            $this->searchResults = User::where('user_type', 1)
                ->where('status', 1)
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('whatsapp_no', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                })
                ->take(20)
                ->get();
                $orders = Order::where('order_number', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('customer', function ($query) {
                        $query->where('name', 'like', '%' . $this->searchTerm . '%');
                    })
                    ->latest()
                    ->take(1)
                    ->get();

        } else {
            // Reset results when the search term is empty
            $this->searchResults = [];
        }
    }
      // Function to validate date
       public function is_valid_date($date) {
            $timestamp = strtotime($date);
            if ($timestamp !== false) {
                return true;
            }
            return false;
        }

      public function selectCustomer($customer_id){
     
            $customer = User::find($customer_id);
            if($customer){
                $this->customer = $customer->name.'('.$customer->phone.')';
                $this->customer_id = $customer->id;
            }else{
                $this->reset(['customer','customer_id',]);
            }
            $this->searchResults = [];
           
      }
    public function ChangePaymentMode($value){
        $this->activePayementMode = $value;
    }
    public function render()
    {
        return view('livewire.accounting.add-payment-receipt');
    }
}
