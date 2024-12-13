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
                'route'=>['dashboard'],
                'icon'=>'dashboard'
            ],
            [
                'name'=>'Customer Management',
                'route'=>['customers.index','admin.user-address-form','admin.customers.edit','admin.customers.details'],
                'icon'=>'group'
            ],
            [
                'name'=>'Supplier Management',
                'route'=>[
                           'suppliers.index',
                           'suppliers.add',
                           'suppliers.edit',
                           'suppliers.details'
                        ],
                'icon'=>'store'
            ]
         ];
    }
    public function render()
    {
        return view('livewire.navigation-menu');
    }
}
