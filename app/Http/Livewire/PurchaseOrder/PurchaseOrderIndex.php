<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderIndex extends Component
{
    public $purchaseOrders = '';

    public function mount(){
        
        $this->purchaseOrders = PurchaseOrder::get();
        // dd($this->purchaseOrders);
    }
    public function render()
    {
        return view('livewire.purchase-order.purchase-order-index');
    }
}
