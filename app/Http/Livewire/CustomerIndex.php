<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CustomerIndex extends Component
{
    public $users;
    public function mount(){
        $this->users = User::all();
    }
    public function render()
    {
        return view('livewire.customer-index');
    }
}
