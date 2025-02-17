<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentCollection;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\AccountingRepositoryInterface;

class AddOrderSlip extends Component
{
    protected $accountingRepository;
    public $order;
    public $errorMessage = [];
    public $order_item = [];
    public $activePayementMode = 'cash';
    public $staffs =[];
    public $payment_collection_id = "";
    public $readonly = "readonly";
    public $customer,$customer_id, $staff_id,$staff_name, $total_amount, $amount, $voucher_no, $payment_date, $payment_mode, $chq_utr_no, $bank_name, $receipt_for = "Customer",$paid_amount;

    public function boot(AccountingRepositoryInterface $accountingRepository)
    {
        $this->accountingRepository = $accountingRepository;
    }
    public function mount($id){

        $this->order = Order::with('items','customer','createdBy')->where('id', $id)->first();
        if($this->order){
            foreach($this->order->items as $key=>$order_item){
                $this->order_item[$key]['id']= $order_item->id;
                $this->order_item[$key]['price']= (int)$order_item->price;
                $this->order_item[$key]['quantity']= $order_item->quantity;
            }
            $this->total_amount = $this->order->total_amount;
            $this->amount = $this->order->total_amount;
            $this->customer = $this->order->customer->name;
            $this->customer_id = $this->order->customer->id;
            $this->staff_id = $this->order->createdBy->id;
            $this->staff_name = $this->order->createdBy->name;
            $this->payment_date = date('Y-m-d');
        }
        $this->voucher_no = 'PAYRECEIPT'.time();
        $this->staffs = User::where('user_type', 0)->where('designation', 2)->select('name', 'id')->orderBy('name', 'ASC')->get();
    }

    public function updateQuantity($value, $key,$price){
        if(!empty($value)){
            $this->order_item[$key]['quantity']= $value;
            $this->order_item[$key]['price']= $price*$value;
            $this->amount = 0;
            foreach($this->order_item as $key=>$item){
                $this->amount +=$item['price'];
            }
        }
    }

    public function submitForm(){
       
        $this->reset(['errorMessage']);
        $this->errorMessage = array();
        foreach ($this->order_item as $key => $item) {
            if (empty($item['quantity'])) {  // Ensure 'quantity' exists
                $this->errorMessage["order_item.$key.quantity"] = 'Please enter quantity.';
            }
        }
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
            // dd($this->all());
            try {
                DB::beginTransaction();
                $this->accountingRepository->StorePaymentReceipt($this->all());
                $this->updateOrder();

                $this->updateOrderItems();

                DB::commit();

                session()->flash('success', 'Payment receipt added successfully.');
                return redirect()->route('admin.accounting.payment_collection');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', $e->getMessage());
            }
        }
       
    }
    public function updateOrder()
    {
        $this->validate([
            'total_amount' => 'required|numeric',
            'customer_id' => 'required|exists:users,id',
            'staff_id' => 'required|exists:users,id',
        ]);

        $order = Order::find($this->order->id);

        if ($order) {
            $order->update([
                'total_amount' => $this->total_amount,
                'customer_id' => $this->customer_id,
                'created_by' => $this->staff_id,
                'last_payment_date' => $this->payment_date,
            ]);
        }
    }
    public function updateOrderItems()
    {
        foreach ($this->order_item as $item) {
            OrderItem::where('id', $item['id'])->update([
                'total_price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }
    }

    public function is_valid_date($date) {
        $timestamp = strtotime($date);
        if ($timestamp !== false) {
            return true;
        }
        return false;
    }
    public function ResetForm(){
        $this->reset(['customer','customer_id','staff_id', 'amount', 'voucher_no', 'payment_date', 'payment_mode', 'chq_utr_no', 'bank_name']);
        $this->voucher_no = 'PAYRECEIPT'.time();
    }
    public function ChangePaymentMode($value){
        $this->activePayementMode = $value;
    }
    public function render()
    {
        return view('livewire.order.add-order-slip');
    }
}
