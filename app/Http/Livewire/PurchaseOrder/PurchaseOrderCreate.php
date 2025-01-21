<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\Collection;
use App\Models\Fabric;
use App\Models\Product;

class PurchaseOrderCreate extends Component
{
    public $suppliers,$collections,$fabrics = [];
    public $selectedCollection = null,$product = null;
    public $isFabricSelected =  [];
    public $rows = []; 
    public $selectedSupplier = null;

    public function mount(){
        $this->suppliers = Supplier::where('status',1)->where('deleted_at',NULL)->get();
        $this->collections = Collection::all()->toArray();
        $this->fabrics = [];
        $this->product = [];
        $this->rows = [
            ['collection' => null, 'fabric' => [], 'product' => [], 'pcs_per_mtr' => 1, 'pcs_per_qty' => 1, 'price_per_pc' => null, 'total_amount' => null],
        ];
    }

    public function SelectedSupplier($value){
        $this->selectedCollection = null;
        $this->product = [];
        $this->fabrics = [];
        $this->isFabricSelected = false;
    }

    // PurchaseOrder Create
    public function savePurchaseOrder()
    {
        $this->validate([
            'selectedSupplier' => 'required',
            'rows.*.collections' => 'required',
            'rows.*.fabric' => 'required_if:rows.*.product,null',
            'rows.*.product' => 'required_if:rows.*.fabric,null',
            'rows.*.pcs_per_mtr' => 'nullable|numeric|min:1',
            'rows.*.pcs_per_qty' => 'nullable|numeric|min:1',
            'rows.*.price_per_pc' => 'required|numeric|min:0',
            'rows.*.total_amount' => 'required|numeric|min:0',
        ], [
            'rows.*.collections.required' => 'The collection field is required.',
            'rows.*.fabric.required_if' => 'The fabric field is required if no product is selected.',
            'rows.*.product.required_if' => 'The product field is required if no fabric is selected.',
            'rows.*.price_per_pc.required' => 'The price per piece is required.',
            'rows.*.total_amount.required' => 'The total amount is required.',
        ]);
    
        // Your saving logic here
    }
    

    public function SelectedCollection($index, $collectionId)
    {
        $this->rows[$index]['collection'] = $collectionId;
        $collection = Collection::find($collectionId);

        if ($collection) {
            if ($collection->id === 1) { // GARMENT
                $this->rows[$index]['fabrics'] = Fabric::where('status', 1)->get()->toArray();
                $this->rows[$index]['products'] = [];
                $this->rows[$index]['fabric'] = null;
                $this->rows[$index]['product'] = null;
                $this->isFabricSelected[$index] = true;
            } elseif (in_array($collection->id, [2, 4])) { // GARMENT ITEMS
                $this->rows[$index]['products'] = Product::where('collection_id', $collection->id)->get()->toArray();
                $this->rows[$index]['fabrics'] = [];
                $this->isFabricSelected[$index] = false;
            } else {
                $this->rows[$index]['fabrics'] = [];
                $this->rows[$index]['products'] = [];
                $this->isFabricSelected[$index] = false;
            }
        } else {
            $this->rows[$index]['fabrics'] = [];
            $this->rows[$index]['products'] = [];
            $this->isFabricSelected[$index] = false;
        }

        $this->rows[$index]['fabric'] = null;
        $this->rows[$index]['product'] = null;
    }

    public function addRow()
    {
        $this->rows[] = ['collection' => null, 'fabric' => [], 'product' => [], 'pcs_per_mtr' => 1, 'pcs_per_qty' => 1, 'price_per_pc' => null, 'total_amount' => null];

    }

    public function removeRow($index){
        unset($this->rows[$index]);
        unset($this->isFabricSelected[$index]);
        $this->rows = array_values($this->rows);
        $this->isFabricSelected = array_values($this->isFabricSelected);
    }

    
    public function render()
    {
        return view('livewire.purchase-order.purchase-order-create');
    }
}
