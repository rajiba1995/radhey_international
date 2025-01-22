<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;

class GenerateGrn extends Component
{
    public $purchaseOrderId;
    public $purchaseOrder;

    public function mount($purchase_order_id){
         $this->purchaseOrderId = $purchase_order_id;
         $this->purchaseOrder = PurchaseOrder::with('orderproducts.product', 'orderproducts.fabric')->find($this->purchaseOrderId);              
    }

    public function render()
    {
        return view('livewire.purchase-order.generate-grn');
    }
}
