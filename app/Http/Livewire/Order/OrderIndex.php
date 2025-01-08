<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\User;

class OrderIndex extends Component
{
    use WithPagination;
    public $customer_id;
    public $created_by; 

    public $search = ''; // For search functionality
    public $status = ''; // For filtering by status
    protected $paginationTheme = 'bootstrap'; // Optional: For Bootstrap styling
    protected $updatesQueryString = ['search'];
    
    public function updatingSearch(){
        $this->resetPage();
    }

    public function mount(){
        $customer_id = request()->query('customer_id');
        if ($customer_id) {
            $this->customer_id = $customer_id;
        }
    }
    
    public function render()
    {
        // Fetch users for the dropdown
        // $users = User::all();
        $this->usersWithOrders = User::whereHas('orders')->get();
        $orders = Order::query()
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_email', 'like', '%' . $this->search . '%');
            })
            ->when($this->created_by, function ($query) {
                $query->where('created_by', $this->created_by);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.order.order-index', [
            'orders' => $orders,
            'usersWithOrders' => $this->usersWithOrders, 
        ]);
    }

}
