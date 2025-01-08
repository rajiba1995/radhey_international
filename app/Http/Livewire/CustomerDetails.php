<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class CustomerDetails extends Component
{
    public $customerId; 
    public $customer;   

    public function mount($id)
    {
        $this->customerId = $id;
        // $this->customer = User::with('address')->findOrFail($this->customerId);

        $this->customer = User::with(['billingAddressLatest', 'shippingAddressLatest'])->findOrFail($this->customerId);
        // dd($this->customer);
    }

    public function render()
    {
        return view('livewire.customer-details');
    }
}
