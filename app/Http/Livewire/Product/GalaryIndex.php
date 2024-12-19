<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Galary;
use App\Models\Product;
use Livewire\WithFileUploads;

class GalaryIndex extends Component
{

    use WithFileUploads;
    public $images = [];
    public $product_id;

    protected $rules = [
        'images' => 'required|array',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',  // Validate each image
    ];

    protected $messages = [
        'images.required' => 'Please upload at least one image.',
        'images.array' => 'Invalid image format.',
        'images.*.image' => 'Each file must be an image.',
        'images.*.mimes' => 'Images must be in jpg, jpeg, or png format.',
        'images.*.max' => 'Each image must not exceed 2MB.',
    ];

    public function mount($product_id)
    {
        $this->product_id = $product_id; 
    }

    // public function resetFields()
    // {
    //     $this->reset(['images']);  // Reset the images field
    // }

    public function save(){
        $this->validate();
        foreach ($this->images as $image) {
           
            $path = $image->store('galaries', 'public');
            $imagePath = 'storage/'.$path;
            
            Galary::create([
                'product_id' => $this->product_id,
                'image' => $imagePath,
            ]);
        }   
        
         session()->flash('message', 'Images uploaded successfully.');
        //  $this->resetFields();
    }

    public function destroy($id){
        $galaries = Galary::find($id);
        if($galaries){
            $imagePath = public_path('storage/'.$galaries->image);

            if(file_exists($imagePath)){
                unlink($imagePath);
            }

            $galaries->delete();
            session()->flash('message','Image deleted successfully');
        }
    }
    
    public function render()
    {
        $product = Product::select('name')->find($this->product_id);
        $product_name = $product->name;
        $galleries = Galary::with('product')->where('product_id', $this->product_id)->get();
        return view('livewire.product.galary-index',['galleries'=>$galleries,'productName'=>$product_name]);
    }
}
