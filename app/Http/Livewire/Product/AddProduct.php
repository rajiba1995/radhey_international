<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Collection;
// use App\Models\CollectionType;
use App\Models\Fabric;
use Livewire\WithFileUploads;

class AddProduct extends Component
{
    use WithFileUploads;
    public $categories;
    public $Collections = [];
    // public $subCategories = [];
    public $collection,$category_id,$sub_category_id,$name,$product_code,$short_description,$description,$gst_details,$product_image;
    public $selectedFabrics = [];
    public $fabrics = [];
    public $multipleImages = [];
    public $showAdditionalImageField = false;

    public function mount()
    {
        // Load categories when the component is mounted
        // $this->categories = Category::where('status', 1)->orderBy('title','ASC')->get() ?? collect();
        // $this->subCategories = []; 
        $this->fabrics = Fabric::all();
        $this->Collections = Collection::orderBy('title', 'ASC')->get() ?? collect();
      
    }

    // public function GetCollection($id){
    //     $this->categories = Category::where('collection_id', $id)->where('status', 1)->orderBy('title','ASC')->get() ?? collect();
    // }
    public function GetCollection($id)
    {
        $selectedCollection = Collection::find($id);

        // Check if the collection is "garment"
        if ($selectedCollection && strtolower($selectedCollection->title) === 'garment') {
            $this->showAdditionalImageField = true;
        } else {
            $this->showAdditionalImageField = false;
        }

        $this->categories = Category::where('collection_id', $id)
            ->where('status', 1)
            ->orderBy('title', 'ASC')
            ->get() ?? collect();
    }

    // public function create()
    // {
    //     $this->validate([
    //         // 'collection_type' => 'required',
    //         'collection' => 'required',
    //         'category_id' => 'required',
    //         // 'sub_category_id' => 'nullable',
    //         'name' => 'required|string|max:255',
    //         'selectedFabrics' => 'required|array',
    //         'selectedFabrics.*' => 'exists:fabrics,id',
    //         'product_code' => 'required|string|max:10',
    //         'short_description' => 'nullable|string|max:255',
    //         'description' => 'nullable|string',
    //         'gst_details' => 'nullable|numeric',
    //         'product_image' => 'nullable|image|max:1024', // 1MB max image size
    //     ]);

    //     // Store the product image in the desired directory
    //     if ($this->product_image) {
    //         $imagePath = $this->product_image->store('uploads/product', 'public');
    //     }

    //     // Create the product record in the database
    //     $product = Product::create([
    //         'collection_id' => $this->collection,
    //         'category_id' => $this->category_id,
    //         // 'sub_category_id' => $this->sub_category_id,
    //         'name' => $this->name,
    //         'product_code' => $this->product_code,
    //         'short_description' => $this->short_description,
    //         'description' => $this->description,
    //         'gst_details' => $this->gst_details,
    //         'product_image' => $imagePath ?? null, // Image path is nullable if no image is uploaded
    //     ]);
        
    //     $product->fabrics()->attach($this->selectedFabrics);
    //     session()->flash('message', 'Product created successfully!');
    //     return redirect()->route('product.view');
    // }

    public function create()
    {
        try {
            // Validate input
            $this->validate([
                'collection' => 'required',
                'category_id' => 'required',
                'name' => 'required|string|max:255',
                'selectedFabrics' => 'required|array',
                'selectedFabrics.*' => 'exists:fabrics,id',
                'product_code' => 'required|string|max:10',
                'short_description' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'gst_details' => 'nullable|numeric',
                'product_image' => 'nullable|image|max:1024', // 1MB max image size
                'multipleImages.*' => 'nullable|image|max:2024|mimes:jpg,jpeg,png,webp', // Validation for multiple images
            ]);
    
            \DB::beginTransaction(); // Start transaction
    
            // Store the main product image
            $imagePath = null;
            if ($this->product_image) {
                $imagePath = $this->product_image->store('uploads/product', 'public');
            }
    
            // Create the product record
            $product = Product::create([
                'collection_id' => $this->collection,
                'category_id' => $this->category_id,
                'name' => $this->name,
                'product_code' => $this->product_code,
                'short_description' => $this->short_description,
                'description' => $this->description,
                'gst_details' => $this->gst_details,
                'product_image' => $imagePath, // Save main image path
            ]);
    
            // Attach selected fabrics
            $product->fabrics()->attach($this->selectedFabrics);
    
            // Store multiple product images in the product_images table
            if (!empty($this->multipleImages)) {
                foreach ($this->multipleImages as $image) {
                    $path = $image->store('uploads/product_images', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                    ]);
                }
            }
    
            \DB::commit(); // Commit transaction
            session()->flash('message', 'Product created successfully with multiple images!');
            return redirect()->route('product.view');
        } catch (\Exception $e) {
            \DB::rollBack(); // Rollback transaction in case of error
    
            // Log the error for debugging
            \Log::error('Error creating product: ' . $e->getMessage());
    
            // Display user-friendly error message
            session()->flash('error', 'An error occurred while creating the product. Please try again.');
            return redirect()->back()->withInput(); // Redirect back with input data
        }
    }
    


    public function render()
    {
        return view('livewire.product.add-product');
    }

    
}
