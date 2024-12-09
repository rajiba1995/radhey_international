<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;


class UpdateProduct extends Component
{

    use WithFileUploads;

    public $product_id;
    public $category_id;
    public $sub_category_id;
    public $name;
    public $hsn_code;
    public $short_description;
    public $description;
    public $gst_details;
    public $product_image;
    public $categories = []; // For categories dropdown
    public $subCategories = []; // For subcategories dropdown
    PUBLIC $existing_image;

    public function mount($product_id)
    {
        $product = Product::findOrFail($product_id);

        $this->product_id = $product->id;
        $this->category_id = $product->category_id;
        $this->sub_category_id = $product->sub_category_id;
        $this->name = $product->name;
        $this->hsn_code = $product->hsn_code;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->gst_details = $product->gst_details;
        $this->categories = Category::all(); // Load categories
        $this->subCategories = SubCategory::where('category_id', $this->category_id)->get(); // Load subcategories
        $this->existing_image = $product->product_image; // Store existing image path
    }

    public function GetSubcat($categoryId)
    {
        $this->subCategories = SubCategory::where('category_id', $categoryId)->get();
    }

    public function update()
    {
        $this->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required|string|max:255',
            'hsn_code' => 'nullable|string|max:50',
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
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'name' => $this->name,
            'hsn_code' => $this->hsn_code,
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
