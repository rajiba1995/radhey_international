<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Helpers\Helper;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

class OrderIndex extends Component
{
    use WithPagination;
    
    public $customer_id;
    public $created_by, $search,$status,$start_date,$end_date; 

    protected $paginationTheme = 'bootstrap'; // Optional: For Bootstrap styling
    
    public function resetForm(){
        $this->reset(['search', 'start_date','end_date','created_by']);
    }
    public function mount(){
    }
    public function FindCustomer($keywords){
        $this->search = $keywords;
    }
    public function AddStartDate($date){
        $this->start_date = $date;
    }
    public function AddEndDate($date){
        $this->end_date = $date;
    }
    public function CollectedBy($staff_id){
        $this->created_by = $staff_id;
    }
    
    public function export()
    {
        return Excel::download(new OrdersExport(
            $this->customer_id,
            $this->created_by,
            $this->start_date,
            $this->end_date,
            $this->search
        ), 'orders.xlsx');
    }

    // public function updateStatus($status, $id)
    // {
    //     $order = Order::find($id); // Fetch the order by ID
        
    //     if ($order) {
    //         $order->update(['status' => $status]);
    //         session()->flash('success', 'Order status updated successfully.');
    //     } else {
    //         session()->flash('error', 'Order not found.');
    //     }
    // }

    
    public function render()
    {
        // Fetch users for the dropdown
        // $users = User::all();
        $this->usersWithOrders = User::whereHas('orders')->get();
        $orders = Order::query()
        ->when($this->search, function ($query) {
            $query->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('customer', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                  });
        })
        ->when($this->created_by, function ($query) {
            $query->where('created_by', $this->created_by);
        })
        ->when($this->start_date, function ($query) {
            $query->whereDate('created_at', '>=', $this->start_date);
        })
        ->when($this->end_date, function ($query) {
            $query->whereDate('created_at', '<=', $this->end_date);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    

        return view('livewire.order.order-index', [
            'orders' => $orders,
            'usersWithOrders' => $this->usersWithOrders, 
        ]);
    }

}
