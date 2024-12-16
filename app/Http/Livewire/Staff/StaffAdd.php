<?php

namespace App\Http\Livewire\Staff;

use Livewire\Component;
use App\Models\Designation;
use App\Models\UserBank;
use App\Models\User;
use App\Models\UserAddress;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;


class StaffAdd extends Component
{
    use WithFileUploads;
    public $designation, $person_name, $email, $mobile, $aadhaar_number, $whatsapp_no,$is_wa_same,$user_id;
    public $image, $user_id_front, $user_id_back;
    public $account_holder_name, $bank_name, $branch_name, $account_no, $ifsc, $monthly_salary, $daily_salary, $travel_allowance;
    public $address, $landmark, $state, $city, $pincode, $country;
    public $designations = [];

    public function mount(){
        $this->designations = Designation::where('status',1)->latest()->get();
    }

    public function save(){
       $this->validate([
            'designation' => 'required',
            'person_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'mobile' => 'required|max:'.env('VALIDATE_MOBILE'),
            'whatsapp_no' => 'required|max:'.env('VALIDATE_WHATSAPP'),
            'aadhaar_number' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'user_id_front' => 'nullable|image|max:2048',
            'user_id_back' => 'nullable|image|max:2048',
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
        $imagePath = $this->image ? $this->image->store('images', 'public') : null;
        $userIdFrontPath = $this->user_id_front ? $this->user_id_front->store('user_ids', 'public') : null;
        $userIdBackPath = $this->user_id_back ? $this->user_id_back->store('user_ids', 'public') : null;

       // 1. Save the data into the users table
       $user = User::create([
        'designation' => $this->designation,
        'name' => $this->person_name,
        'email' => $this->email,
        'phone' => $this->mobile,
        'aadhar_name' => $this->aadhaar_number,
        'whatsapp_no' => $this->whatsapp_no,
        'image' =>  $imagePath,
        'user_id_front' =>  $userIdFrontPath,
        'user_id_back' => $userIdBackPath
    ]);

   

    // 2. Save the data into the user_banks table
    UserBank::create([
        'user_id' => $user->id,
        'account_holder_name' => $this->account_holder_name,
        'bank_name' => $this->bank_name,
        'branch_name' => $this->branch_name,
        'bank_account_no' => $this->account_no,
        'ifsc' => $this->ifsc,
        'monthly_salary' => $this->monthly_salary,
        'daily_salary' => $this->daily_salary,
        'travelling_allowance' => $this->travel_allowance,
    ]);

     // 3. Save the data into the user_address table
     UserAddress::create([
        'user_id' => $user->id,
        'address' => $this->address,
        'landmark' => $this->landmark,
        'state' => $this->state,
        'city' => $this->city,
        'zip_code' => $this->pincode,
        'country' => $this->country,
    ]);

      session()->flash('message','Staff information saved successfully!');
      return redirect()->route('staff.index');
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
        return view('livewire.staff.staff-add');
    }
}
