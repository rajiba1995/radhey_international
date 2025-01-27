<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderIndex extends Component
{
    public $purchaseOrders = '';

    public function mount(){
        // Eager load the relationships for purchase orders
        $this->purchaseOrders = PurchaseOrder::with('orderproducts.product', 'orderproducts.fabric', 'orderproducts.collection')->get();
    }
    
    public function render()
    {
        return view('livewire.purchase-order.purchase-order-index');
    }
}
