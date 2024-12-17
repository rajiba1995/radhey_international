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
    public  $code, $title, $status = 1, $fabricId;
    public $search = '';

    public function mount()
    {
        $this->fabrics = Fabric::orderBy('id', 'desc')->get();
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
            'code' => [
                'required',
                'string',
                'max:255',
                'unique:fabrics,code', // Ensure code is unique in fabrics table
            ],
        ]);
        

        Fabric::create([
            'title' => $this->title,
            'code' => $this->code,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Fabric created successfully!');
        return redirect()->route('admin.fabrics.index');
    }

    // Edit Fabric
    public function edit($id)
    {
        $fabric = Fabric::findOrFail($id);
        $this->fabricId = $fabric->id;
        $this->title = $fabric->title;
        $this->code = $fabric->code;
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
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fabrics', 'code')->ignore($this->fabricId),
            ],
        ]);
        
        $fabric = Fabric::findOrFail($this->fabricId);
        $fabric->update([
            'title' => $this->title,
            'code' => $this->code,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Fabric updated successfully!');
        return redirect()->route('admin.fabrics.index');
    }

    // Delete Fabric
    public function destroy($id)
    {
        Fabric::findOrFail($id)->delete();
        session()->flash('message', 'Fabric deleted successfully!');
        return redirect()->route('admin.fabrics.index');
    }

    // Toggle Status
    public function toggleStatus($id)
    {
        $fabric = Fabric::findOrFail($id);
        $fabric->update(['status' => !$fabric->status]);
        session()->flash('message', 'Fabric status updated successfully!');
    }

    // Reset Form Fields
   
  
    public function updatePositions(Request $request)
    {
        try {
            $sortOrder = $request->sortOrder;
    
            // Check if sortOrder is a string, then decode it; otherwise, use it directly
            if (is_string($sortOrder)) {
                $sortOrder = json_decode($sortOrder, true);
            }
    
            if (!is_array($sortOrder)) {
                return response()->json(['error' => 'Invalid data format'], 400);
            }
    
            foreach ($sortOrder as $item) {
                Fabric::where('id', $item['id'])->update(['position' => $item['position']]);
            }
    
            // Flash message after successful update
            session()->flash('message', 'Positions updated successfully!');
    
            // Redirect to the index route with the subcategory_id
            return redirect()->route('admin.fabrics.index');
        } catch (\Exception $e) {
            Log::error('Error updating positions: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
    



    
    // Render Method with Search and Pagination
    public function render()
    {
        $fabrics = Fabric::where('title', 'like', "%{$this->search}%")
            ->orWhere('code', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.product.fabric-index', [
            'fabrics' => $fabrics,
        ]);
    }
}