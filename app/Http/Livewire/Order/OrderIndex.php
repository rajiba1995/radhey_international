<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class OrderIndex extends Component
{
    use WithPagination;
    public $search = ''; // For search functionality
    public $status = ''; // For filtering by status
    protected $paginationTheme = 'bootstrap'; // Optional: For Bootstrap styling
    public function updatingSearch(){
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.order.order-index');
    }
}
