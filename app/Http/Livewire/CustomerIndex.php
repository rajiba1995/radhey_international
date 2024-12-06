<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;
    
  

    

    public function deleteCustomer($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            session()->flash('success', 'Customer deleted successfully.');
        } else {
            session()->flash('error', 'Customer not found.');
        }
    }

    public function render()
    {
        $users = User::paginate(5);
        return view('livewire.customer-index', compact('users'));
    }
}
