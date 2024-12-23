<?php

namespace App\Http\Livewire\Staff;

use Livewire\Component;
use App\Models\Designation;
use App\Models\UserBank;
use App\Models\User;
use App\Models\UserAddress;
use App\Helpers\Helper;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class StaffAdd extends Component
{
    use WithFileUploads;
    public $designation, $person_name, $email, $mobile, $aadhaar_number, $whatsapp_no,$is_wa_same,$user_id;
    public $image, $user_id_front, $user_id_back;
    public $account_holder_name, $bank_name, $branch_name, $account_no, $ifsc, $monthly_salary, $daily_salary, $travel_allowance;
    public $address, $landmark, $state, $city, $pincode, $country;
    public $designations = [];

    public function mount(){
        $this->designations = Designation::where('status',1)->orderBy('name', 'ASC')->where('id', '!=', 1)->get();
    }

    public function save(){
       $this->validate([
            'designation' => 'required',
            'person_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'mobile' => 'required|digits:' . env('VALIDATE_MOBILE') . '|unique:users,phone,' . ($this->user_id ?? 'null'),
            'whatsapp_no' => 'required|digits:'.env('VALIDATE_WHATSAPP'),
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
    //    DB::beginTransaction();

    //    try {
        // Check and upload the images only if they are provided
            $imagePath = $this->image ? Helper::uploadImage($this->image, 'staff2') : null;
            $userIdFrontPath = $this->user_id_front ? Helper::uploadImage($this->user_id_front, 'staff') : null;
            $userIdBackPath = $this->user_id_back ? Helper::uploadImage($this->user_id_back, 'staff') : null;

            // Now, you can handle these paths accordingly (e.g., store them in the database)


            // 1. Save the data into the users table
            $user = User::create([
                'user_type' => 0, //for Staff
                'designation' => $this->designation ?? "",
                'name' => ucwords($this->person_name) ?? "",
                'email' => $this->email ?? "",
                'phone' => $this->mobile ?? "",
                'aadhar_name' => $this->aadhaar_number ?? "",
                'whatsapp_no' => $this->whatsapp_no ?? "",
                'image' =>  $imagePath ?? "",
                'user_id_front' =>  $userIdFrontPath ?? "",
                'user_id_back' => $userIdBackPath ?? "",
                'password'=>Hash::make('1234')
            ]);

            // 2. Save the data into the user_banks table
            UserBank::create([
                'user_id' => $user->id,
                'account_holder_name' => $this->account_holder_name ?? "",
                'bank_name' => $this->bank_name ?? "",
                'branch_name' => $this->branch_name ?? "",
                'bank_account_no' => $this->account_no ?? "",
                'ifsc' => $this->ifsc ?? "",
                'monthly_salary' => is_numeric($this->monthly_salary) ? $this->monthly_salary : null,
                'daily_salary' => is_numeric($this->daily_salary) ?  $this->daily_salary : null,
                'travelling_allowance' => is_numeric($this->travel_allowance) ? $this->travel_allowance : null,
            ]);

            // 3. Save the data into the user_address table
            UserAddress::create([
                'user_id' => $user->id,
                'address_type' => 1, //for Staff
                'address' => $this->address ?? "",
                'landmark' => $this->landmark ?? "",
                'state' => $this->state ?? "",
                'city' => $this->city ?? "",
                'zip_code' => $this->pincode ?? "",
                'country' => $this->country ?? "",
            ]);

            // Commit the transaction if everything is successful
            // DB::commit();

            session()->flash('message', 'Staff information saved successfully!');
            return redirect()->route('staff.index');
        // } catch (\Exception $e) {
        //     // Rollback the transaction in case of an error
        //     DB::rollBack();

        //     // Handle the exception (e.g., log the error and show an error message)
        //     session()->flash('error', 'An error occurred while saving staff information: ' . $e->getMessage());
        //     return back()->withInput();
        // }
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
