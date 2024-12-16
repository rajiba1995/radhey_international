<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NavigationMenu extends Component
{
    public $modules = [];
    public function mount(){
         // Define the modules dynamically
         $this->modules = [
            [
                'name'=>'Dashboard',
                'route'=>'dashboard',
                'icon'=>'dashboard'
            ],
            [
                'name'=>'Customer Management',
                'route'=>'customers.index',
                'icon'=>'dashboard'
            ],
            [
                'name'=>'Supplier Management',
                'route'=>'suppliers.index',
                'icon'=>'dashboard'
            ]
         ];
    }
    public function render()
    {
        return view('livewire.navigation-menu');
    }
}
