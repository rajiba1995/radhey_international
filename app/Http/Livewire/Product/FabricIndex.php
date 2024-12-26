<?php

namespace App\Http\Livewire\Product;


use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\Fabric;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Livewire\WithFileUploads;



class FabricIndex extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $fabrics;
    public  $image, $title, $status = 1, $fabricId,$product_id;
    public $search = '';

    public function mount($product_id)
    {
        $this->product_id = $product_id;
        $this->fabrics = Fabric::orderBy('id', 'desc')->where('product_id', $this->product_id)->get();
    }

    public function store()
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'unique:fabrics,title', 
            ],
            'image' => [
                'required',
                'mimes:jpg,png,jpeg,gif',
                'unique:fabrics,image', 
            ],
        ]);
        
        if($this->image){
            $imagePath = $this->image->store("fabrics",'public');
            $absolutePath = "storage/".$imagePath;
        }
        

        Fabric::create([
            'product_id' => $this->product_id,
            'title' => $this->title,
            'image' =>  $absolutePath,
            'status' => $this->status,
        ]);
        
        $this->title = null;
        $this->image = null;
        
        // Refresh the fabrics list for the current product
       $this->fabrics = Fabric::orderBy('id', 'desc')->where('product_id', $this->product_id)->get();
        session()->flash('message', 'Fabric created successfully!');
        // $this->fabrics = Fabric::orderBy('id', 'desc')->get();
        
    }

    // Edit Fabric
    public function edit($id)
    {
        $fabric = Fabric::findOrFail($id);
        $this->fabricId = $fabric->id;
        $this->title = $fabric->title;
        $this->image = $fabric->image;
        $this->status = $fabric->status;
    }
    // Update Fabric
    public function update()
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fabrics', 'title')->ignore($this->fabricId), 
            ],
            'image' => [
                'required',
                'mimes:jpg,png,jpeg,gif',
                Rule::unique('fabrics', 'image')->ignore($this->fabricId),
            ],
        ]);
        
        $fabric = Fabric::findOrFail($this->fabricId);
        $imagePath = $fabric->image;
        if ($this->image) {
            // Store new image
            $newImagePath = $this->image->store("fabrics", 'public');
            $imagePath = "storage/" . $newImagePath;
        }
        $fabric->update([
            'product_id' => $this->product_id,
            'title' => $this->title,
            'image' => $imagePath,
            'status' => $this->status,
        ]);
        
        $this->title = null;
        $this->image = null;

        // Refresh the fabrics list for the current product
         $this->fabrics = Fabric::orderBy('id', 'desc')->where('product_id', $this->product_id)->get();  
        session()->flash('message', 'Fabric updated successfully!');
       
        // return redirect()->route('admin.fabrics.index');
    }

    // Delete Fabric
    public function destroy($id)
    {
        Fabric::findOrFail($id)->delete();
        session()->flash('message', 'Fabric deleted successfully!');
        $this->fabrics = Fabric::orderBy('id', 'desc')->get();
    }

    // Toggle Status
    public function toggleStatus($id)
    {
        $fabric = Fabric::findOrFail($id);
        $fabric->update(['status' => !$fabric->status]);
        session()->flash('message', 'Fabric status updated successfully!');
    }

    
    // Render Method with Search and Pagination
    public function render()
    {
        $fabrics = Fabric::where('title', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.product.fabric-index', [
            'fabrics' => $fabrics,
        ]);
    }
}