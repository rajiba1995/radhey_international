<?php

namespace App\Http\Livewire\Staff;

use Livewire\Component;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User_Role;
use Illuminate\Support\Facades\Session;

class DesignationIndex extends Component
{   
    public $designations = [];
    public $designationId;
    public $name;
    public $roles = [];
    public $allRoles = [];

    public function mount(){
        $this->allRoles = Role::all();

       // Fetch designations with related data
    $this->designations = Designation::withCount('users') // Get the count of users related to the designation
        ->with(['roles' => function ($query) {
            $query->select('roles.id', 'roles.name'); // Load roles for each designation
        }])
        ->latest()
        ->paginate(10); 

    // Check the designations data with the roles
    // dd($this->designations);

    // Manually add a comma-separated list of role names to each designation
    $this->designations = $this->designations->map(function ($designation) {
        // Manually add a comma-separated list of role names to each designation
        $designation->role_names = $designation->roles->pluck('name')->implode(', ');
        return $designation;
    });
       
    }
    public function storeOrUpdate(){
        // dd($this->designationId);
        $this->validate([
            'name'=>'required|max:200|unique:designation,name,' . $this->designationId,
            'roles'=>'array'
        ]);

        foreach ($this->roles as $roleId) {
            $role = Role::find($roleId);
            if (!$role) {
                session()->flash('error', "Role with ID {$roleId} does not exist.");
                return;
            }
        }
    
        if ($this->designationId) {
            // Update existing designation
            $designation = Designation::findOrFail($this->designationId);
            $designation->update(['name' => $this->name]);
            // Sync roles with the designation
            $designation->roles()->sync($this->roles);  // This syncs the roles 
            Session::flash('message', 'Designation updated successfully');
        }else {
            // Create new designation
            $designation = Designation::create(['name' => $this->name]);

            if (!empty($this->roles)) {
                $designation->roles()->sync($this->roles); // Sync roles to the new designation
            }
    

            Session::flash('message', 'Designation created successfully');
        }
        // Reset form
        $this->resetForm();
        return redirect()->route('staff.designation');


    }

    public function edit($id)
    {
        // Find the designation by ID
        $designation = Designation::findOrFail($id);
        
        // Set the component's properties for editing
        $this->designationId = $designation->id;
        $this->name = ucwords($designation->name);
        
        // Fetch the roles assigned to the designation
        $this->roles = $designation->roles->pluck('id')->toArray();
       
        // Reload designations after editing
        $this->designations = Designation::withCount('users')
        ->with(['roles' => function ($query) {
            $query->select('roles.id', 'roles.name');
        }])
        ->latest()
        ->paginate(10);

        // Manually add a comma-separated list of role names
        $this->designations = $this->designations->map(function ($designation) {
            $designation->role_names = $designation->roles->pluck('name')->implode(', ');
            return $designation;
        });
    }

    public function toggleStatus($id){
        $designation  = Designation::findOrFail($id);
        $designation->status = !$designation->status;
        $designation->save();
        session()->flash('message','Designation Status Updated Successfully');
    }

    public function resetForm()
    {
        $this->reset(['designationId', 'name', 'roles']);
    }



    public function render()
    {
        return view('livewire.staff.designation-index',[
            'designations' => $this->designations,
            'roleList'=>$this->allRoles
        ]);
    }
}
