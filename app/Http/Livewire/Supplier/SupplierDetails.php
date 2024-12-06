<?php

namespace App\Http\Livewire\Supplier;

use App\Models\Supplier;
use Livewire\Component;

class SupplierDetails extends Component
{
    public $supplier;

    public function mount($id)
    {
        $this->supplier = Supplier::find($id);
    }

    public function render()
    {
        return view('livewire.supplier.supplier-details');
    }
}
