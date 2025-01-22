<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Models\PurchaseOrder; // Ensure to use the correct model
use Livewire\Component;

class PurchaseOrderEdit extends Component
{
    public $purchase_order_id;
    public $purchaseOrder,$selectedSupplier; // Add this property to store the order

    // Mount method to initialize the component
    public function mount($purchase_order_id)
    {
        // Store the purchase order in a property
        $this->purchase_order_id = $purchase_order_id;
        $this->purchaseOrder = PurchaseOrder::findOrFail($purchase_order_id); // Fetch the purchase order
    }

    public function render()
    {
        // Return the view for the component
        return view('livewire.purchase-order.purchase-order-edit');
    }
}
