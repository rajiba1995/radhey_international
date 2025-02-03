<?php

namespace App\Http\Livewire\BusinessType;

use Livewire\Component;
use App\Models\BusinessType;    

class BusinessTypeIndex extends Component
{
    public $businessTypeId;
    public $title;
    public $business_types;

    protected $rules = [
        'title' => 'required',
    ];
    public function mount(){
        $this->reloadBusinessTypes();
    }

    public function resetFields(){
        $this->businessTypeId = null;
        $this->title = '';
    }

    public function reloadBusinessTypes(){
        $this->business_types = BusinessType::all();
    }

    public function storeBusinessType(){
        $this->validate();
        
        $business_type = BusinessType::create([
            'title' => $this->title,
        ]);

        session()->flash('message','Business Type Created Successfully');
        $this->resetFields();
        $this->reloadBusinessTypes();
    }

    public function edit($id){
        $business_type = BusinessType::findOrFail($id);
        $this->businessTypeId = $business_type->id;
        $this->title = $business_type->title;
    }

    public function updateBusinessType(){
        $business_type = BusinessType::findOrFail($this->businessTypeId);
        $business_type->update([
            'title' => $this->title,
        ]);
        
        session()->flash('message','Business Type Updated Successfully');
        $this->resetFields();
        $this->reloadBusinessTypes();
    }

    public function render()
    {
        return view('livewire.business-type.business-type-index');
    }
}
