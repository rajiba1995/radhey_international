<?php

namespace App\Http\Livewire\Accounting;

use Livewire\Component;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\PaymentCollection;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class PaymentCollectionIndex extends Component
{
    use WithPagination;
    public $searchResults = [];
    public $data = [];
    public $total;
    public $staff_id;
    public $staffs = [];
    public $selected_customer;
    public $selected_customer_id;
    public $active_details = 0;

    public function mount(){
        $this->staffs = User::where('user_type', 0)->where('designation', 2)->select('name', 'id')->orderBy('name', 'ASC')->get();
    }

    public function CollectionData(){
        $desg = Auth::user()->designation;
        $paginate = 20;
        $customer_id = $this->selected_customer_id;
        $staff_id = $this->staff_id;
    
        $query = PaymentCollection::with(['customer', 'user'])
            ->when(!empty($customer_id), function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->when(!empty($staff_id), function ($query) use ($staff_id) {
                $query->where('user_id', $staff_id);
            })
            ->orderBy('cheque_date', 'desc');
    
        $this->total = $query->count();
        return $query->paginate($paginate);
    }
    public function resetForm(){
        $this->active_details = 0;
        $this->reset(['selected_customer','selected_customer_id', 'staff_id']);
    }
    public function FindCustomer($term){
        $this->searchResults = Helper::GetCustomerDetails($term);
    }

    public function selectCustomer($customer_id){
        $customer = User::find($customer_id);
        if($customer){
            $this->selected_customer = $customer->name.'('.$customer->phone.')';
            $this->selected_customer_id = $customer->id;
        }else{
            $this->reset(['selected_customer','selected_customer_id']);
        }
        $this->searchResults = [];
    }

    public function CollectedBy($value){
        $this->staff_id = $value;
    }

    public function customerDetails($id){
        $this->active_details = $id;
    }
    public function render()
    {
        $paginatedData = $this->CollectionData();
        return view('livewire.accounting.payment-collection-index', [
            'paymentData' => $paginatedData,
        ]);
    }
}
