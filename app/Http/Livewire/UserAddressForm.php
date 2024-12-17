<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserAddress;
use Livewire\WithFileUploads;

class UserAddressForm extends Component
{
    use WithFileUploads;

    public $id,$name, $company_name, $email, $phone, $whatsapp_no,$is_wa_same, $gst_number, $credit_limit, $credit_days,$gst_certificate_image,$image,$verified_video;
    public $address_type, $address, $landmark, $city, $state, $country, $zip_code;
    public $billing_address;
    public $billing_landmark;
    public $billing_city;
    public $billing_state;
    public $billing_country;
    public $billing_pin;

    public $is_billing_shipping_same;

    public $shipping_address;
    public $shipping_landmark;
    public $shipping_city;
    public $shipping_state;
    public $shipping_country;
    public $shipping_pin;

    public $tempImageUrl;

    // Function to watch for changes in is_billing_shipping_same
    public function toggleShippingAddress()
    {
        // When the checkbox is checked
        if ($this->is_billing_shipping_same) {
            // Copy billing address to shipping address
            $this->shipping_address = $this->billing_address;
            $this->shipping_landmark = $this->billing_landmark;
            $this->shipping_city = $this->billing_city;
            $this->shipping_state = $this->billing_state;
            $this->shipping_country = $this->billing_country;
            $this->shipping_pin = $this->billing_pin;
        } else {
            // Reset shipping address fields
            $this->shipping_address = '';
            $this->shipping_landmark = '';
            $this->shipping_city = '';
            $this->shipping_state = '';
            $this->shipping_country = '';
            $this->shipping_pin = '';
        }
    }
    // public $address_id;
    public function rules()
    {
        // Base rules
        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'required|mimes:jpeg,png,jpg,gif',
            'verified_video' => 'nullable|mimes:mp4,mov,avi,wmv',
            'company_name'=>'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|digits:'.env('VALIDATE_MOBILE'),
            'whatsapp_no' => 'required|digits:'.env('VALIDATE_WHATSAPP'),
            'gst_number' => 'nullable|string|max:15',
            'credit_limit' => 'nullable|numeric',
            'credit_days' => 'nullable|integer',
            // Billing address validation
            'billing_address' => 'required|string',
            'billing_landmark' => 'nullable|string',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string',
            'billing_country' => 'required|string',
            'billing_pin' => 'nullable|string',
        ];
    
        // Conditional shipping address rules based on the checkbox
        if (!$this->is_billing_shipping_same) {
            $rules['shipping_address'] = 'required|string';
            $rules['shipping_landmark'] = 'nullable|string';
            $rules['shipping_city'] = 'required|string';
            $rules['shipping_state'] = 'required|string';
            $rules['shipping_country'] = 'required|string';
            $rules['shipping_pin'] = 'nullable|string';
        } else {
            // If the shipping address is the same as the billing address, make the fields optional
            $rules['shipping_address'] = 'nullable|string';
            $rules['shipping_landmark'] = 'nullable|string';
            $rules['shipping_city'] = 'nullable|string';
            $rules['shipping_state'] = 'nullable|string';
            $rules['shipping_country'] = 'nullable|string';
            $rules['shipping_pin'] = 'nullable|string';
        }
    
        return $rules;
    }
    

    // public function mount($userId = null)
    // {
    //     if ($userId) {
    //         $this->userId = $userId;
    //         $this->loadUserData();
    //     }
    // }
    private function uploadImage()
    {
        if ($this->image) {
            $timestamp = now()->timestamp;
            $extension = $this->image->getClientOriginalExtension();
            $imageName = $timestamp . '.' . $extension;
            return $this->image->storeAs('profile_image', $imageName, 'public');
        }
        return null;
    }
    

    private function uploadVideo()
{
    if ($this->verified_video) {
        $timestamp = now()->timestamp;
        $extension = $this->verified_video->getClientOriginalExtension();
        $videoName = $timestamp . '.' . $extension;

        // Store the video and return the path
        return $this->verified_video->storeAs('verified_video', $videoName, 'public');
    }
    return null;
}

    public function updatedImage(){
        if($this->image){
            $this->tempImageUrl = $this->image->temporaryUrl();
        }
    }


    private function uploadGSTCertificate()
{
    // Handle file upload
    if ($this->gst_certificate_image) {
        $timestamp = now()->timestamp;
        $imageName = $timestamp . '-' . $this->gst_certificate_image->getClientOriginalExtension();
        return $this->gst_certificate_image->storeAs('gst_certificate_image', $imageName, 'public');
    }
    return null;
}
   
    public function save()
    {
        $this->validate();
        // Check if a user already exists and delete the old image if necessary
        if ($this->id) { 
            $existingUser = User::find($this->id);
            if ($existingUser && $existingUser->profile_image) {
                // Delete the old image from storage
                \Storage::disk('public')->delete($existingUser->profile_image);
            }
        }

        // Store user data
        $imagePath = $this->uploadImage(); 
        $videoPath = $this->verified_video ? $this->uploadVideo() : null;
       
        $userData = [
            'name' => $this->name,
            'profile_image' => $imagePath,
            'verified_video' =>  $videoPath,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp_no' => $this->whatsapp_no,
            'gst_number' => $this->gst_number,
            'credit_limit' => $this->credit_limit,
            'credit_days' => $this->credit_days,
            'gst_certificate_image' => $this->gst_certificate_image ? $this->uploadGSTCertificate() : null, // Handle file upload
        ];
       
            
        $user = User::create($userData);
         // Store billing address
         $this->storeAddress($user->id, 1, $this->billing_address, $this->billing_landmark, $this->billing_city, $this->billing_state, $this->billing_country, $this->billing_pin);

         // Check if shipping address is the same as billing address
        if(!$this->is_billing_shipping_same){
             // Store shipping address separately
            $this->storeAddress($user->id, 2, $this->shipping_address, $this->shipping_landmark, $this->shipping_city, $this->shipping_state, $this->shipping_country, $this->shipping_pin);
        }else{
            // Store shipping address as the same as billing
            $this->storeAddress($user->id, 2, $this->billing_address, $this->billing_landmark, $this->billing_city, $this->billing_state, $this->billing_country, $this->billing_pin);
        }

        session()->flash('success', 'Customer information saved successfully!');
        return redirect()->route('customers.index');

    }

    public function SameAsMobile(){
        if($this->is_wa_same == 0){
            $this->whatsapp_no = $this->phone;
            $this->is_wa_same =1;
        }else{
            $this->whatsapp_no = '';
            $this->is_wa_same = 0;
        }
    }

    private function storeAddress($userId,$addressType,$address,$landmark,$city,$state,$country,$zipCode){
        // Store address in the user_address table
        Useraddress::create([
            'user_id'=>$userId,
            'address_type'=>$addressType,
            'address'=>$address,
            'landmark'=>$landmark,
            'city'=>$city,
            'state'=>$state,
            'country'=>$country,
            'zip_code'=>$zipCode
        ]);
    }   
    

    public function render()
    {
        return view('livewire.user-address-form');
    }
}
