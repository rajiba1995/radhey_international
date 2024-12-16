<?php

namespace App\Http\Livewire\Supplier;

use App\Models\Supplier;
use Livewire\Component;

class SupplierIndex extends Component
{
    public $suppliers;

    public function mount()
    {
        $this->suppliers = Supplier::all();
    }

    public function toggleStatus($id){
        $supplier = Supplier::find($id);
        $supplier->status = !$supplier->status;
        $supplier->save();
        $this->suppliers = Supplier::all();
        session()->flash('success','Supplier status updated successfully');
    }

    public function render()
    {
        return view('livewire.supplier.supplier-index');
    }

    public function deleteSupplier($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->delete();
            $this->suppliers = Supplier::all();  // Re-fetch suppliers after deletion
        }
    }
}
