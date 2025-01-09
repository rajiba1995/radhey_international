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
    public $start_date;
    public $end_date;
    protected $paginationTheme = 'bootstrap'; // Optional: For Bootstrap styling
    protected $updatesQueryString = ['search','start_date','end_date'];
    
    public function updatingSearch(){
        $this->resetPage();
    }

    public function mount(){
        $customer_id = request()->query('customer_id');
        if ($customer_id) {
            $this->customer_id = $customer_id;
        }
        $this->start_date = request()->query('start_date',null);
        $this->end_date = request()->query('end_date',null);
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
            ->when($this->customer_id , function($query){
                $query->where('customer_id',$this->customer_id);
            })
            ->when($this->created_by, function ($query) {
                $query->where('created_by', $this->created_by);
            })
            ->when($this->start_date , function($query){
                $query->whereDate('created_at', '>=' ,$this->start_date);
            })
            ->when($this->end_date , function($query){
                $query->whereDate('created_at','<=' , $this->end_date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.order.order-index', [
            'orders' => $orders,
            'usersWithOrders' => $this->usersWithOrders, 
        ]);
    }

}
