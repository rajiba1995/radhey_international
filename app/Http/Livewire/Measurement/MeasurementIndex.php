<?php

namespace App\Http\Livewire\Measurement;


use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\Measurement;
use App\Models\Product;
use Illuminate\Http\Request;


class MeasurementIndex extends Component
{
    public $measurements;
    public $product_id, $short_code, $title, $status = 1, $measurementId;
    public $search = '';

    public function mount($product_id)
    {
        $this->product_id = $product_id; // Initialize with the passed product
        $this->measurements = Measurement::where('product_id', $product_id) ->orderBy('position', 'asc')->get();
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
                Rule::unique('measurements')->where(function ($query) {
                    return $query->where('product_id', $this->product_id);
                }),
            ],
            'short_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('measurements')->where(function ($query) {
                    return $query->where('product_id', $this->product_id);
                }),
            ],
        ]);

        Measurement::create([
            'product_id' => $this->product_id,
            'title' => $this->title,
            'short_code' => $this->short_code,
            'status' => $this->status,
        ]);
        $this->filterData();
        session()->flash('message', 'Measurement created successfully!');
        // return redirect()->route('measurements.index', ['product' => $this->product_id]);
    }

    // Edit Measurement
    public function edit($id)
    {
        $measurement = Measurement::findOrFail($id);
        $this->measurementId = $measurement->id;
        $this->product_id = $measurement->product_id;
        $this->title = $measurement->title;
        $this->short_code = $measurement->short_code;
        $this->status = $measurement->status;
    }
    // Update Measurement
    public function update()
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('measurements')->where(function ($query) {
                    return $query->where('product_id', $this->product_id);
                })->ignore($this->measurementId),
            ],
            'short_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('measurements')->where(function ($query) {
                    return $query->where('product_id', $this->product_id);
                })->ignore($this->measurementId),
            ],
        ]);
        $measurement = Measurement::findOrFail($this->measurementId);
        $measurement->update([
            'product_id' => $this->product_id,
            'title' => $this->title,
            'short_code' => $this->short_code,
            'status' => $this->status,
        ]);
        $this->filterData();
        session()->flash('message', 'Measurement updated successfully!');
        // return redirect()->route('measurements.index', ['product_id' => $this->product_id]);
    }

    // Delete Measurement
    public function destroy($id)
    {
        Measurement::findOrFail($id)->delete();
        session()->flash('message', 'Measurement deleted successfully!');
        $this->filterData();
    }

    // Toggle Status
    public function toggleStatus($id)
    {
        $measurement = Measurement::findOrFail($id);
        $measurement->update(['status' => !$measurement->status]);
        session()->flash('success', 'Measurement status updated successfully!');
    }

    // Reset Form Fields
    public function resetFields()
    {
        $this->measurementId = null;
        $this->product_id = null;
        $this->title = '';
        $this->short_code = '';
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
                Measurement::where('id', $item['id'])->update(['position' => $item['position']]);
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
        return Measurement::where('product_id', $this->product_id)->orderBy('position', 'asc')->paginate(10);
        $this->short_code = null;
        $this->title = null;
    }

    
    // Render Method with Search and Pagination
    public function render()
    {
        $subCat = Product::select('name')->find($this->product_id);
        $measurements = Measurement::where('title', 'like', "%{$this->search}%")
            ->orWhere('short_code', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.measurement.measurement-index', [
            'measurements' => $measurements,
            'products' => $subCat->name,
        ]);
    }
}