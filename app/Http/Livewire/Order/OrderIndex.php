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
        // Query the orders table with search and status filters
        $orders = Order::query()
            ->when($this->search, function($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->orderBy('created_at', 'desc') // You can customize the ordering as needed
            ->paginate(10); // Paginate the results, 10 orders per page

        // Return the view and pass the orders data to it
        return view('livewire.order.order-index', [
            'orders' => $orders
        ]);
    }

    public function invoice($id){
        dd($id);
    }
}
