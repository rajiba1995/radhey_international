<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Livewire\WithFileUploads;
use App\Models\Collection;
use App\Models\CollectionType;
use Illuminate\Support\Facades\Log;


class UpdateProduct extends Component
{

    use WithFileUploads;

    public $product_id;
    public $collection;
    public $category_id;
    // public $sub_category_id;
    public $name;
    public $product_code;
    public $short_description;
    public $description;
    public $gst_details;
    public $product_image;
    public $categories = []; // For categories dropdown
    public $subCategories = []; // For subcategories dropdown
    public $existing_image;
    public $Collections = [];

    public function mount($product_id)
    {
        $product = Product::findOrFail($product_id);

        $this->product_id = $product->id;
        $this->collection = $product->collection_id;
        $this->category_id = $product->category_id;
        // $this->sub_category_id = $product->sub_category_id;
        $this->name = $product->name;
        $this->product_code = $product->product_code;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->gst_details = $product->gst_details;
        // Load categories based on the product's collection
        $this->categories = Category::where('collection_id', $this->collection)
                                    ->where('status', 1) 
                                    ->orderBy('title', 'ASC')
                                    ->get();
        // $this->subCategories = SubCategory::where('category_id', $this->category_id)->get(); // Load subcategories
        $this->existing_image = $product->product_image; // Store existing image path
        $this->Collections = Collection::orderBy('title', 'ASC')->get();
    }
    // Collection wise category
    public function GetCollection($id){
        $this->categories = Category::where('collection_id', $id)->where('status', 1)->orderBy('title','ASC')->get() ?? collect();
    }
    
    // public function GetSubcat($categoryId)
    // {
    //     $this->subCategories = SubCategory::where('category_id', $categoryId)->get();
    // }

  
    public function update()
    {
        // dd($this->all());
        $this->validate([
            // 'collection_type' => 'required',
            'collection' => 'required',
            'category_id' => 'required',
            // 'sub_category_id' => 'required',
            'name' => 'required|string|max:255',
            'product_code' => 'required|string|max:50',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'gst_details' => 'nullable|numeric|min:0',
            'product_image' => 'nullable|image|max:1024', // 1MB Max
        ]);

        // Handle product image
        $imagePath = $this->existing_image; // Keep the existing image by default
        if ($this->product_image) {
            $imagePath = $this->product_image->store('uploads/product', 'public');

            // Optionally delete the old image
            if ($this->existing_image && \Storage::disk('public')->exists($this->existing_image)) {
                \Storage::disk('public')->delete($this->existing_image);
            }
        }

        $product = Product::findOrFail($this->product_id);

        $product->update([
            'collection_id' => $this->collection,
            'category_id' => $this->category_id,
            // 'sub_category_id' => $this->sub_category_id,
            'name' => $this->name,
            'product_code' => $this->product_code,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'gst_details' => $this->gst_details,
            'product_image' => $this->product_image 
                ? $this->product_image->store('products', 'public') 
                : $product->product_image, // Keep existing image if not changed
        ]);

        session()->flash('message', 'Product updated successfully!');
        return redirect()->route('product.view');
    }


    public function render()
    {
        return view('livewire.product.update-product');
    }
}
