<?php

namespace App\Http\Livewire\Product;


use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\Fabric;
use Illuminate\Http\Request;


class FabricIndex extends Component
{
    public $fabrics;
    public  $hexacode, $title, $status = 1, $fabricId,$product_id;
    public $search = '';

    public function mount($product_id)
    {
        $this->product_id = $product_id;
        $this->fabrics = Fabric::orderBy('id', 'desc')->where('product_id', $product_id)->get();
    }

    public function store()
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'unique:fabrics,title', // Ensure title is unique in fabrics table
            ],
            'hexacode' => [
                'required',
                'string',
                'max:255',
                'unique:fabrics,hexacode', // Ensure hexacode is unique in fabrics table
            ],
        ]);
        

        Fabric::create([
            'product_id' => $this->product_id,
            'title' => $this->title,
            'hexacode' => $this->hexacode,
            'status' => $this->status,
        ]);
        $this->title = null;
        $this->hexacode = null;

        session()->flash('message', 'Fabric created successfully!');
        $this->fabrics = Fabric::orderBy('id', 'desc')->get();
        // return redirect()->route('admin.fabrics.index');
    }

    // Edit Fabric
    public function edit($id)
    {
        $fabric = Fabric::findOrFail($id);
        $this->fabricId = $fabric->id;
        $this->title = $fabric->title;
        $this->hexacode = $fabric->hexacode;
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
            'hexacode' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fabrics', 'hexacode')->ignore($this->fabricId),
            ],
        ]);
        
        $fabric = Fabric::findOrFail($this->fabricId);
        $fabric->update([
            'product_id' => $this->product_id,
            'title' => $this->title,
            'hexacode' => $this->hexacode,
            'status' => $this->status,
        ]);
        $this->title = null;
        $this->hexacode = null;


        session()->flash('message', 'Fabric updated successfully!');
        $this->fabrics = Fabric::orderBy('id', 'desc')->get();
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
            ->orWhere('hexacode', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.product.fabric-index', [
            'fabrics' => $fabrics,
        ]);
    }
}