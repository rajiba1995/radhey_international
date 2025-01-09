<?php

namespace App\Http\Livewire\Staff;

use Livewire\Component;
use App\Models\User;
use App\Models\Designation;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;


class StaffUpdate extends Component
{
    use WithFileUploads;

    public $staff, $designation, $person_name, $email, $mobile, $aadhaar_number, $whatsapp_no;
    public $image, $user_id_front, $user_id_back;
    public $account_holder_name, $bank_name, $branch_name, $account_no, $ifsc;
    public $monthly_salary, $daily_salary, $travel_allowance;
    public $address, $landmark, $state, $city, $pincode, $country;
    public $staff_id,$is_wa_same,$designations,$user_id;  // Variable to hold the staff id

    public function mount($staff_id){
        $this->staff = User::with(['bank','address','designationDetails'])->find($staff_id);
        // dd($this->staff);
        $this->designations = Designation::latest()->get();
         // If staff exists, assign the data to the public variables
         if ($this->staff) {
            $this->designation = $this->staff->designationDetails->id;
            $this->person_name = $this->staff->name;
            $this->email = $this->staff->email;
            $this->mobile = $this->staff->phone;
            $this->aadhaar_number = $this->staff->aadhar_name;
            $this->whatsapp_no = $this->staff->whatsapp_no;
            $this->image = $this->staff->image;
            $this->user_id_front = $this->staff->user_id_front;
            $this->user_id_back = $this->staff->user_id_back;
            // Bank Information
            $this->account_holder_name = $this->staff->bank->account_holder_name;
            $this->bank_name = $this->staff->bank->bank_name;
            $this->branch_name = $this->staff->bank->branch_name;
            $this->account_no = $this->staff->bank->bank_account_no;
            $this->ifsc = $this->staff->bank->ifsc;
            $this->monthly_salary = $this->staff->bank->monthly_salary;
            $this->daily_salary = $this->staff->bank->daily_salary;
            $this->travel_allowance = $this->staff->bank->travelling_allowance;
              // Address Information
            $this->address = $this->staff->address->address;
            $this->landmark = $this->staff->address->landmark;
            $this->state = $this->staff->address->state;
            $this->city = $this->staff->address->city;
            $this->pincode = $this->staff->address->zip_code;
            $this->country = $this->staff->address->country;
        }
    }
    public function update(){
    try {
        $this->validate([
            'designation' => 'required',
            'person_name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($this->staff->id),
            ],
            'mobile' => [
                'required',
                'regex:/^\d{' . env('VALIDATE_MOBILE', 8) . ',}$/',
            ],
            'whatsapp_no' => [
                'required',
                'regex:/^\d{' . env('VALIDATE_WHATSAPP', 8) . ',}$/',
            ],
            'aadhaar_number' => 'nullable|numeric',
            'image' => 'nullable|max:2048',
            'user_id_front' => 'nullable|max:2048',
            'user_id_back' => 'nullable|max:2048',
            'account_holder_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:20',
            'ifsc' => 'nullable|string|max:11',
            'monthly_salary' => 'required|numeric',
            'daily_salary' => 'nullable|numeric',
            'travel_allowance' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
       ]);
       // Store the files
       $imagePath = $this->image && $this->image instanceof \Illuminate\Http\UploadedFile ? $this->image->store('images', 'public') : $this->staff->image;
       $userIdFrontPath = $this->user_id_front && $this->user_id_front instanceof \Illuminate\Http\UploadedFile ? $this->user_id_front->store('user_ids', 'public') : $this->staff->user_id_front;
       $userIdBackPath = $this->user_id_back && $this->user_id_back instanceof \Illuminate\Http\UploadedFile ? $this->user_id_back->store('user_ids', 'public') : $this->staff->user_id_back;
       
         // Update the staff record
         $this->staff->update([
            'name' => $this->person_name ?? '',
            'email' => $this->email ?? '',
            'phone' => $this->mobile ?? '',
            'aadhar_name' => $this->aadhaar_number ?? '',
            'whatsapp_no' => $this->whatsapp_no ?? '',
            'image' => $imagePath ?? '',
            'user_id_front' => $userIdFrontPath ?? '',
            'user_id_back' => $userIdBackPath ?? '',
        ]);
        // Update bank details
        if ($this->staff->bank) {
            $this->staff->bank->update([
                'account_holder_name' => $this->account_holder_name ?? '',
                'bank_name' => $this->bank_name ?? '',
                'branch_name' => $this->branch_name ?? '',
                'bank_account_no' => $this->account_no ?? '',
                'ifsc' => $this->ifsc ?? '',
                'monthly_salary' => is_numeric($this->monthly_salary) ? $this->monthly_salary : null,
                'daily_salary' => is_numeric($this->daily_salary) ?  $this->daily_salary : null,
                'travelling_allowance' => is_numeric($this->travel_allowance) ? $this->travel_allowance : null,
            ]);
        } else {
            // If no bank record, create a new one
            $this->staff->bank()->create([
                'account_holder_name' => $this->account_holder_name ?? '',
                'bank_name' => $this->bank_name ?? '',
                'branch_name' => $this->branch_name ?? '',
                'bank_account_no' => $this->account_no ?? '',
                'ifsc' => $this->ifsc ?? '',
                'monthly_salary' => is_numeric($this->monthly_salary) ? $this->monthly_salary : null,
                'daily_salary' => is_numeric($this->daily_salary) ?  $this->daily_salary : null,
                'travelling_allowance' => is_numeric($this->travel_allowance) ? $this->travel_allowance : null,
            ]);
        }
         // Update address details
         if ($this->staff->address) {
            $this->staff->address->update([
                'address' => $this->address ?? '',
                'address_type' => 1,
                'landmark' => $this->landmark ?? '',
                'state' => $this->state ?? '',
                'city' => $this->city ?? '',
                'zip_code' => $this->pincode ?? '',
                'country' => $this->country ?? '',
            ]);
        } else {
            // If no address record, create a new one
            $this->staff->address()->create([
                'address' => $this->address ?? '',
                'address_type' => 1,
                'landmark' => $this->landmark ?? '',
                'state' => $this->state ?? '',
                'city' => $this->city ?? '',
                'zip_code' => $this->pincode ?? '',
                'country' => $this->country ?? '',
            ]);
        }

        session()->flash('message', 'Staff updated successfully');
        return redirect()->route('staff.index');
    } catch (\Exception $e) {
        // If any error occurs, catch it and flash an error message
        session()->flash('error', 'There was an error while updating the staff: ' . $e->getMessage());
        dd($e->getMessage());
        // Optionally log the error for debugging purposes
        \Log::error('Staff update failed: ' . $e->getMessage());

        // Redirect back to the edit page or wherever necessary
        return back();
    }
}
    public function SameAsMobile(){
        if($this->is_wa_same == 0){
            $this->whatsapp_no = $this->mobile;
            $this->is_wa_same =1;
        }else{
            $this->whatsapp_no = '';
            $this->is_wa_same = 0;
        }
    }

    public function render()
    {
        return view('livewire.staff.staff-update',['designations'=>$this->designations]);
    }
}
