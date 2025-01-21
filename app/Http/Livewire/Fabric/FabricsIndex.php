<?php

namespace App\Http\Livewire\Fabric;


use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\Fabric;
use App\Models\Product;
use Illuminate\Http\Request;


class FabricsIndex extends Component
{
    public $fabrics;
    public  $title,$product_id, $status = 1, $fabricId,$image;
    public $search = '';

    public function mount($product_id)
    {
        // $this->product_id = $product_id; // Initialize with the passed product
        // $this->fabrics = Fabric::with('products')->get();


        $this->product = Product::with('fabrics')->findOrFail($product_id);
        $this->fabrics = $this->product->fabrics;
        // dd($this->fabrics);
    }
    public function rules()
    {
        return [
            
        ];
    }


    public function store()
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fabrics')->where(function ($query) {
                    return $query->where('product_id', $this->product_id);
                }),
            ],
            // 'short_code' => [
            //     'required',
            //     'string',
            //     'max:255',
            //     Rule::unique('fabrics')->where(function ($query) {
            //         return $query->where('product_id', $this->product_id);
            //     }),
            // ],
        ]);

        Fabric::create([
            'product_id' => $this->product_id,
            'title' => $this->title,
            // 'short_code' => $this->short_code,
            'status' => $this->status,
        ]);
        $this->filterData();
        session()->flash('message', 'Fabric created successfully!');
        // return redirect()->route('fabrics.index', ['product' => $this->product_id]);
    }

    // Edit Fabric
    public function edit($id)
    {
        $fabric = Fabric::findOrFail($id);
        $this->fabricId = $fabric->id;
        // $this->product_id = $fabric->product_id;
        $this->title = $fabric->title;
        // $this->short_code = $fabric->short_code;
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
                Rule::unique('fabrics')->where(function ($query) {
                    return $query->where('product_id', $this->product_id);
                })->ignore($this->fabricId),
            ],
            'short_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fabrics')->where(function ($query) {
                    return $query->where('product_id', $this->product_id);
                })->ignore($this->fabricId),
            ],
        ]);
        $fabric = Fabric::findOrFail($this->fabricId);
        $fabric->update([
            'product_id' => $this->product_id,
            'title' => $this->title,
            'short_code' => $this->short_code,
            'status' => $this->status,
        ]);
        $this->filterData();
        session()->flash('message', 'Fabric updated successfully!');
        // return redirect()->route('fabrics.index', ['product_id' => $this->product_id]);
    }

    // Delete Fabric
    public function destroy($id)
    {
        Fabric::findOrFail($id)->delete();
        session()->flash('message', 'Fabric deleted successfully!');
        $this->filterData();
    }

    // Toggle Status
    public function toggleStatus($id)
    {
        $fabric = Fabric::findOrFail($id);
        $fabric->update(['status' => !$fabric->status]);
        session()->flash('success', 'Fabric status updated successfully!');
    }

    // Reset Form Fields
    public function resetFields()
    {
        $this->fabricId = null;
        $this->product_id = null;
        $this->title = '';
        // $this->short_code = '';
        $this->status = 1;
    }
  
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
    
            // Redirect to the index route with the product_id
            $this->filterData();
        } catch (\Exception $e) {
            Log::error('Error updating positions: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
    

   public function filterData(){
        return Fabric::where('product_id', $this->product_id)->orderBy('position', 'asc')->paginate(10);
        // $this->short_code = null;
        $this->title = null;
    }

    
    // Render Method with Search and Pagination
    public function render()
    {
        $subCat = Product::select('name')->find($this->product_id);
        $fabrics = Fabric::where('title', 'like', "%{$this->search}%")
            // ->orWhere('short_code', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.product.fabric-index', [
            'fabrics' => $fabrics,
            'products' => $subCat->name,
        ]);
    }
}