<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class MasterProduct extends Component
{

    public function deleteProduct($product_id){
        $product = Product::findOrFail($product_id);
        if($product->product_image && \Storage::disk('public')->exists($product->product_image)){
            \Storage::disk('public')->delete($product->product_image);
        }
        $product->delete();
        session()->flash('message','Product deleted successfully');
        return redirect()->route('viewProducts');
    }

    public function toggleStatus($product_id){
        $product = Product::findOrFail($product_id);
        $product->status = !$product->status;
        $product->save();
        session()->flash('message', 'Product status updated successfully.');
    }

    public function render()
    {
        $products = Product::with('category','sub_category')->latest()->get();
        return view('livewire.product.master-product',['products'=>$products]);
    }
}
