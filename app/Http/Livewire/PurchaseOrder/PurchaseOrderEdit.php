<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Livewire\Component;

class PurchaseOrderEdit extends Component
{
    public $purchase_order_id;
    public $purchaseOrder,$selectedSupplier,$rows; 

    public function mount($purchase_order_id)
    {
        // Store the purchase order in a property
        $this->purchase_order_id = $purchase_order_id;
        $this->purchaseOrder = PurchaseOrder::with('orderproducts.product', 'orderproducts.fabric')->findOrFail($purchase_order_id);
        // Set default selected supplier
        $this->selectedSupplier = $this->purchaseOrder->supplier_id;
        $this->suppliers = Supplier::all();
        // Set rows for items
        $this->rows = $this->purchaseOrder->orderproducts->map(function ($item) {
            return [
                'collections' => $item->collection_id,
                'fabric' => $item->fabric_id,
                'product' => $item->product_id,
                'pcs_per_qty' => $item->qty_in_pieces,
                'pcs_per_mtr' => $item->qty_in_meter,
                'price_per_pc' => $item->piece_price,
                'total_amount' => $item->total_price,
                'fabrics' => [$item->fabric], 
                'products' => [$item->product], 
            ];
        })->toArray();
        
    }

    public function render()
    {
        // Return the view for the component
        return view('livewire.purchase-order.purchase-order-edit');
    }
}
