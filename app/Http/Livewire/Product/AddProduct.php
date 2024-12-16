<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Livewire\WithFileUploads;

class AddProduct extends Component
{
    use WithFileUploads;
    public $categories;
    public $subCategories = [];
    public $category_id,$sub_category_id,$name,$hsn_code,$short_description,$description,$gst_details,$product_image;
   
    public function mount()
    {
        // Load categories when the component is mounted
        $this->categories = Category::where('status', 1)->get() ?? collect();
        $this->subCategories = []; 
    }


    public function create()
    {
        $this->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required|string|max:255',
            'hsn_code' => 'nullable|string|max:10',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'gst_details' => 'nullable|numeric',
            'product_image' => 'nullable|image|max:1024', // 1MB max image size
        ]);

        // Store the product image in the desired directory
        if ($this->product_image) {
            $imagePath = $this->product_image->store('uploads/product', 'public');
        }

        // Create the product record in the database
        Product::create([
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'name' => $this->name,
            'hsn_code' => $this->hsn_code,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'gst_details' => $this->gst_details,
            'product_image' => $imagePath ?? null, // Image path is nullable if no image is uploaded
        ]);

        session()->flash('message', 'Product created successfully!');
        return redirect()->route('product.view');
    }

    public function GetSubcat($category_id){
        $this->subCategories = SubCategory::where('category_id', $category_id)->where('status', 1)  // Ensure only active sub-categories
        ->get() ?? collect();
        $this->sub_category_id = null; // Reset sub-category when category changes
    }

    public function render()
    {
        return view('livewire.product.add-product');
    }

    
}
