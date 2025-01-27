<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Collection;

class MasterProduct extends Component
{
    public $productData;
    public $collection;
    public $searchFilter;

    public function mount(){
        $this->collection = Collection::all();
    }
    public function deleteProduct($product_id){
        $product = Product::findOrFail($product_id);
        if($product->product_image && \Storage::disk('public')->exists($product->product_image)){
            \Storage::disk('public')->delete($product->product_image);
        }
        $product->deleted_at = now(); 
        $product->save();
        session()->flash('message','Product deleted successfully.');
    }

    public function toggleStatus($product_id){
        $product = Product::findOrFail($product_id);
        $product->status = !$product->status;
        $product->save();
        session()->flash('message', 'Product status updated successfully.');
    }

    public function render()
    {
        $query = Product::with('category','sub_category')->whereNull('deleted_at');
        // filter by selected collection
        if($this->searchFilter){
            $query->where('collection_id',$this->searchFilter);
        }

        $products = $query->latest()->get();
        return view('livewire.product.master-product',['products'=>$products]);
    }
}
