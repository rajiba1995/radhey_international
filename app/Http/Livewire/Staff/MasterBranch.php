<?php

namespace App\Http\Livewire\Staff;

use Livewire\Component;
use App\Models\Branch;

class MasterBranch extends Component
{
    public $branchId;
    public $name,$email,$mobile,$whatsapp,$city,$address;
    public $branchNames;

    protected $rules = [
        'name' => 'required|unique:branches,name',
        'email'=> 'required|email|unique:branches,email',
        'mobile' => 'required|numeric|unique:branches,mobile',
        'whatsapp' => 'required|numeric|unique:branches,whatsapp',
        'city' => 'required',
        'address' => 'required',
    ];

    public function mount(){
        $this->reloadBranch();
    }

    public function reloadBranch(){
        $this->branchNames = Branch::all();
    }

    public function resetFields(){
        $this->branchId = null;
        $this->name = '';
        $this->email = '';
        $this->mobile = '';
        $this->whatsapp = '';
        $this->city = '';
        $this->address = '';
    }

    public function storeBranch(){
       $this->validate();     
       Branch::create([
           'name'=> $this->name,
           'email'=> $this->email,
           'mobile'=> $this->mobile,
           'whatsapp'=> $this->whatsapp,
           'city'=> $this->city,
           'address'=> $this->address,
       ]);

       session()->flash('message','Branch Created Successfully');
       $this->resetFields();
       $this->reloadBranch();
    }

    public function edit($id){
        $branch = Branch::find($id);
        $this->branchId = $id;
        $this->name = $branch->name;
        $this->email = $branch->email;
        $this->mobile = $branch->mobile;
        $this->whatsapp = $branch->whatsapp;
        $this->city = $branch->city;
        $this->address = $branch->address;
    }
    public function updateBranch(){
        $this->validate([
            'name' => 'required|unique:branches,name,' . $this->branchId,
            'email'=> 'required|email|unique:branches,email,' . $this->branchId,
            'mobile' => 'required|numeric|unique:branches,mobile,' . $this->branchId,
            'whatsapp' => 'required|numeric|unique:branches,whatsapp,' . $this->branchId,
            'city' => 'required',
            'address' => 'required',
        ]);
        Branch::find($this->branchId)->update([
            'name'=> $this->name,
            'email'=> $this->email,
            'mobile'=> $this->mobile,
            'whatsapp'=> $this->whatsapp,
            'city'=> $this->city,
            'address'=> $this->address,
        ]);

        session()->flash('message','Branch Updated Successfully');
        $this->resetFields();
        $this->reloadBranch();
    }


    public function render()
    {
        return view('livewire.staff.master-branch');
    }
}
