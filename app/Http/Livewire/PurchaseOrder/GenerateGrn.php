<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Helpers\Helper;
use App\Models\Stock;
use App\Models\StockProduct;
use App\Models\StockFabric;


class GenerateGrn extends Component
{
    public $purchaseOrderId;
    public $purchaseOrder;
    public $uniqueNumber;
    // Product
    public $selectedBulkIn = []; 
    public $selectedUniqueNumbers = [];
    // Fabric
    public $selectedFabricBulkIn=[];
    public $selectedFabricUniqueNumbers=[];
    // Generate Grn
    public $productTotalPrice = 0;
    public $fabricTotalPrice = 0;
    public $totalPrice = 0;
    

    public function mount($purchase_order_id){
         $this->purchaseOrderId = $purchase_order_id;
         $this->purchaseOrder = PurchaseOrder::with('orderproducts.product', 'orderproducts.fabric','orderproducts.collection')->find($this->purchaseOrderId);    
         $this-> uniqueNumber = Helper::generateUniqueNumber();      
    }

    public function toggleFabricUniqueNumbers($orderProductId){
        if(in_array($orderProductId,$this->selectedFabricBulkIn)){
            $this->selectedFabricUniqueNumbers[] = $orderProductId;
        }else{
            $this->selectedFabricUniqueNumbers = array_diff($this->selectedFabricUniqueNumbers, [$orderProductId]);
        }
    }

    public function toggleUniqueNumbersForProduct($orderProductId, $rowCount)
    {
        if (in_array($orderProductId, $this->selectedBulkIn)) {
            $this->selectedUniqueNumbers[$orderProductId] = range(0, $rowCount - 1); 
        } else {
            unset($this->selectedUniqueNumbers[$orderProductId]);
        }
    }

    // Generate GRN
    public function generateGrn()
    {
        try {
            if (count($this->selectedFabricUniqueNumbers) > 0 || count($this->selectedUniqueNumbers) > 0) {
                $this->productTotalPrice = 0;
                $this->fabricTotalPrice = 0;
                $productIds = [];
                $fabricIds = [];
    
                // Calculate total prices for fabrics
                if (count($this->selectedFabricUniqueNumbers) > 0) {
                    foreach ($this->selectedFabricUniqueNumbers as $orderProductId) {
                        $fabric = $this->purchaseOrder->orderproducts->find($orderProductId);
                        $this->fabricTotalPrice += $fabric->qty_in_meter * $fabric->piece_price;
                    }
                }
    
                // Calculate total prices for products
                if (count($this->selectedUniqueNumbers) > 0) {
                    foreach ($this->selectedUniqueNumbers as $orderProductId => $uniqueNumbers) {
                        $product = $this->purchaseOrder->orderproducts->find($orderProductId);
                        $this->productTotalPrice += $product->qty_in_pieces * $product->piece_price;
                    }
                }
    
                // Calculate the overall total price
                $this->totalPrice = $this->productTotalPrice + $this->fabricTotalPrice;
    
                $grn_no = "GRN-" . $this->uniqueNumber;
                // Collect Fabric IDs
                if (count($this->selectedFabricUniqueNumbers) > 0) {
                    foreach ($this->selectedFabricUniqueNumbers as $orderProductId) {
                        $product = $this->purchaseOrder->orderproducts->find($orderProductId);
                        $fabricIds[] = $product->fabric->id; // Collect only fabric IDs
                    }
                }
    
                // Collect Product IDs
                if (count($this->selectedUniqueNumbers) > 0) {
                    foreach ($this->selectedUniqueNumbers as $orderProductId => $uniqueNumbers) {
                        $product = $this->purchaseOrder->orderproducts->find($orderProductId);
                        $productIds[] = $product->product->id; // Collect only product IDs
                    }
                }

                $productIds = array_unique($productIds);
                 $fabricIds = array_unique($fabricIds);
                 
                $stocks = new Stock();
                $stocks->grn_no = $grn_no;
                $stocks->purchase_order_id = $this->purchaseOrderId;
                $stocks->po_unique_id = $this->purchaseOrder->unique_id;
                $stocks->goods_in_type = 'goods_in';
                
                // Remove duplicates and set IDs in Stock
                $stocks->product_ids = implode(',',$productIds);
                $stocks->fabric_ids = implode(',', $fabricIds);
                $stocks->total_price = $this->totalPrice;
                $stocks->save();
    
                // Insert Stock Products if only products are selected or both are selected
                if (count($this->selectedUniqueNumbers) > 0) {
                    foreach ($this->selectedUniqueNumbers as $orderProductId => $uniqueNumbers) {
                        $product = $this->purchaseOrder->orderproducts->find($orderProductId);
                        foreach ($uniqueNumbers as $number) {
                            $stockProduct = new StockProduct();
                            $stockProduct->stock_id = $stocks->id;
                            $stockProduct->product_id = $product->product->id;
                            $stockProduct->qty_in_pieces = $product->qty_in_pieces;
                            $stockProduct->piece_price = $product->piece_price;
                            $stockProduct->total_price = $stockProduct->piece_price * $stockProduct->qty_in_pieces;
                            $stockProduct->save();
                        }
                    }
                }
    
                // Insert Stock Fabrics if only fabrics are selected or both are selected
                if (count($this->selectedFabricUniqueNumbers) > 0) {
                    foreach ($this->selectedFabricUniqueNumbers as $orderProductId) {
                        $fabric = $this->purchaseOrder->orderproducts->find($orderProductId);
                        $stockFabric = new StockFabric();
                        $stockFabric->stock_id = $stocks->id;
                        $stockFabric->fabric_id = $fabric->fabric->id;
                        $stockFabric->qty_in_meter = $fabric->qty_in_meter;
                        $stockFabric->piece_price = $fabric->piece_price;
                        $stockFabric->total_price = $stockFabric->piece_price * $stockFabric->qty_in_meter;
                        $stockFabric->save();
                            
                    }
                }
                
                $this->purchaseOrder->status = 1;
                $this->purchaseOrder->save();
              
                session()->flash('success', 'GRN Generated Successfully');
                return redirect()->route('purchase_order.index');
            } else {
                session()->flash('error', 'Please Select Products or Fabrics');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            // Log the error and flash a user-friendly message
            \Log::error('Error generating GRN: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while generating the GRN. Please try again.');
            return redirect()->back();
        }
    }
    
    public function render()
    {
        return view('livewire.purchase-order.generate-grn',[
            'uniqueNumber' => $this->uniqueNumber
        ]);
    }
}
