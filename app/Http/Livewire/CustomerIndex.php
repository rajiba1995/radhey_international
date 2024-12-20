<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public $search;
    protected $updatesQueryString = ['search'];

    public function deleteCustomer($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            session()->flash('success', 'Customer deleted successfully');
        } else {
            session()->flash('error', 'Customer not found');
        }
    }

    public function toggleStatus($id){
        $user = User::find($id);
        $user->status = !$user->status;
        $user->save();
        session()->flash('success','Customer status updated successfully');
    }

    public function render()
    {
        $users = User::where('user_type',1)
        ->when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->paginate(1);
        
        return view('livewire.customer-index', compact('users'));
    }
}
