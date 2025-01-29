<?php

namespace App\Http\Livewire\Staff;

use Livewire\Component;
use App\Models\Designation;
use App\Models\UserBank;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Country;
use App\Helpers\Helper;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class StaffAdd extends Component
{
    use WithFileUploads;
    public $designation, $person_name, $email, $mobile, $aadhaar_number, $whatsapp_no,$is_wa_same,$user_id;
    public $image, $passport_id_front, $passport_id_back, $passport_expiry_date;
    public $account_holder_name, $bank_name, $branch_name, $account_no, $ifsc, $monthly_salary, $daily_salary, $travel_allowance;
    public $address, $landmark, $state, $city, $pincode, $country;
    public $designations = [];
    public $Selectcountry;
    public $selectedCountryId;
    public $showAadhaarStar = false;
    public $emergency_contact_person,$emergency_mobile,$emergency_whatsapp,$emergency_address,$same_as_contact;
    public function mount(){
        $this->designations = Designation::where('status',1)->orderBy('name', 'ASC')->where('id', '!=', 1)->get();
        $this->Selectcountry = Country::all();
        $this->selectedCountryId = null;
    }

    public function SelectedCountry()
    {
        $this->showAadhaarStar = $this->selectedCountryId == 1;
    }

    public function save(){
        // dd($this->all());
        $aadhaarValidationRule = $this->selectedCountryId == 1 ? 'required|numeric' : 'nullable|numeric';
       $this->validate([
            'designation' => 'required',
            'person_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
          'mobile' => [
                'required',
                'regex:/^\d{' . env('VALIDATE_MOBILE', 8) . ',}$/', // At least VALIDATE_MOBILE digits
                'unique:users,phone,' . ($this->user_id ?? 'null'),
            ],
            'whatsapp_no' => [
                'required',
                'regex:/^\d{' . env('VALIDATE_WHATSAPP', 8) . ',}$/', // At least VALIDATE_WHATSAPP digits
            ],
            'aadhaar_number' => $aadhaarValidationRule,
            'image' => 'nullable|image|max:2048',
            'passport_id_front' => 'nullable|image|max:2048',
            'passport_id_back' => 'nullable|image|max:2048',
            'passport_expiry_date' => 'nullable',
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
       DB::beginTransaction();

       try {
        // Check and upload the images only if they are provided
            $imagePath = $this->image ? Helper::uploadImage($this->image, 'staff2') : null;
            $passportIdFrontPath = $this->passport_id_front ? Helper::uploadImage($this->passport_id_front, 'staff') : null;
            $passportIdBackPath = $this->passport_id_back ? Helper::uploadImage($this->passport_id_back, 'staff') : null;

            // Now, you can handle these paths accordingly (e.g., store them in the database)


            // 1. Save the data into the users table
            $user = User::create([
                'country_id'=> $this->selectedCountryId,
                'user_type' => 0, //for Staff
                'designation' => $this->designation ?? "",
                'name' => ucwords($this->person_name) ?? "",
                'email' => $this->email ?? "",
                'phone' => $this->mobile ?? "",
                'aadhar_name' => $this->aadhaar_number ?? "",
                'whatsapp_no' => $this->whatsapp_no ?? "",
                'image' =>  $imagePath ?? "",
                'passport_id_front' =>  $passportIdFrontPath ?? "",
                'passport_id_back' => $passportIdBackPath ?? "",
                'passport_expiry_date' => $this->passport_expiry_date ? $this->passport_expiry_date : null,
                'password'=>Hash::make('secret'),
                'emergency_contact_person' => $this->emergency_contact_person ?? "",
                'emergency_mobile' => $this->emergency_mobile ?? "",
                'emergency_whatsapp' => $this->emergency_whatsapp ?? "",
                'emergency_address' => $this->emergency_address ?? "",
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
            DB::commit();

            session()->flash('message', 'Staff information saved successfully!');
            return redirect()->route('staff.index');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Handle the exception (e.g., log the error and show an error message)
            session()->flash('error', 'An error occurred while saving staff information: ' . $e->getMessage());
            dd($e->getMessage());
            return back()->withInput();
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

    public function SameAsContact(){
        if($this->same_as_contact == 0){
            $this->emergency_whatsapp = $this->emergency_mobile;
            $this->same_as_contact = 1;
        }else{
            $this->emergency_whatsapp = '';
            $this->same_as_contact = 0;
        }

    }


    public function render()
    {
        return view('livewire.staff.staff-add');
    }
}
