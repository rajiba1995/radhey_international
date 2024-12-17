<?php

namespace App\Http\Livewire\Measurement;


use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\Measurement;


class MeasurementIndex extends Component
{
    public $measurements;
    public $subcategory_id, $short_code, $title, $status = 1, $measurementId;
    public $search = '';

    public function mount($subcategory)
    {
        $this->subcategory_id = $subcategory; // Initialize with the passed subcategory
        $this->measurements = Measurement::where('subcategory_id', $subcategory)->latest()->get();
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
                    return $query->where('subcategory_id', $this->subcategory_id);
                }),
            ],
            'short_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('measurements')->where(function ($query) {
                    return $query->where('subcategory_id', $this->subcategory_id);
                }),
            ],
        ]);

        Measurement::create([
            'subcategory_id' => $this->subcategory_id,
            'title' => $this->title,
            'short_code' => $this->short_code,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Measurement created successfully!');
        return redirect()->route('measurements.index', ['subcategory' => $this->subcategory_id]);
    }

    // Edit Measurement
    public function edit($id)
    {
        $measurement = Measurement::findOrFail($id);
        $this->measurementId = $measurement->id;
        $this->subcategory_id = $measurement->subcategory_id;
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
                    return $query->where('subcategory_id', $this->subcategory_id);
                }),
            ],
            'short_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('measurements')->where(function ($query) {
                    return $query->where('subcategory_id', $this->subcategory_id);
                }),
            ],
        ]);
        $measurement = Measurement::findOrFail($this->measurementId);
        $measurement->update([
            'subcategory_id' => $this->subcategory_id,
            'title' => $this->title,
            'short_code' => $this->short_code,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Measurement updated successfully!');
        return redirect()->route('measurements.index', ['subcategory' => $this->subcategory_id]);
    }

    // Delete Measurement
    public function destroy($id)
    {
        Measurement::findOrFail($id)->delete();
        session()->flash('message', 'Measurement deleted successfully!');
        return redirect()->route('measurements.index', ['subcategory' => $this->subcategory_id]);
    }

    // Toggle Status
    public function toggleStatus($id)
    {
        $measurement = Measurement::findOrFail($id);
        $measurement->update(['status' => !$measurement->status]);
        session()->flash('message', 'SubCategory status updated successfully!');
    }

    // Reset Form Fields
    public function resetFields()
    {
        $this->measurementId = null;
        $this->subcategory_id = null;
        $this->title = '';
        $this->short_code = '';
        $this->status = 1;
    }
    public function updatePosition(Request $request)
    {
        $sortOrder = json_decode($request->sortOrder, true); // Decode JSON

        foreach ($sortOrder as $item) {
            Measurement::where('id', $item['id'])->update(['position' => $item['position']]);
        }

        return response()->json(['message' => 'Positions updated successfully!']);
    }

    
    // Render Method with Search and Pagination
    public function render()
    {
        $measurements = Measurement::where('title', 'like', "%{$this->search}%")
            ->orWhere('short_code', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.measurement.measurement-index', [
            'measurements' => $measurements,
        ]);
    }
}