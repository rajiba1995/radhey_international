<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserAddress;
use Livewire\WithFileUploads;

class UserAddressForm extends Component
{
    use WithFileUploads;

    public $name, $company_name, $email, $phone, $whatsapp_no, $gst_number, $credit_limit, $credit_days,$gst_certificate_image;
    public $address_type, $address, $landmark, $city, $state, $country, $zip_code;
    // public $address_id;
    protected $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:10',
            'whatsapp_no' => 'required|string|max:10',
            'gst_number' => 'required|string|max:15',
            'credit_limit' => 'required|numeric',
            'credit_days' => 'required|integer',
            'address_type' => 'required|string|max:50',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'zip_code' => 'required|string|max:15',
            'gst_certificate_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'company_name' => 'required|string|max:255',
            'landmark' => 'required|string|max:255',
    ];

    public function mount($userId = null)
    {
        if ($userId) {
            $this->userId = $userId;
            $this->loadUserData();
        }
    }

    // public function loadUserData()
    // {
    //     $user = User::find($this->userId);
    //     $this->name = $user->name;
    //     $this->company_name = $user->company_name;
    //     $this->email = $user->email;
    //     $this->location = $user->location;
    //     $this->phone = $user->phone;
    //     $this->about = $user->about;

    //     // Load the user's address if it exists
    //     $address = UserAddress::where('user_id', $this->userId)->first();
    //     if ($address) {
    //         $this->address_id = $address->id;
    //         $this->address_type = $address->address_type;
    //         $this->address = $address->address;
    //         $this->landmark = $address->landmark;
    //         $this->city = $address->city;
    //         $this->state = $address->state;
    //         $this->country = $address->country;
    //         $this->zip_code = $address->zip_code;
    //     }
    // }

    // public function save1()
    // {
    //     // Validate the user data
    //     $this->validate([
    //         'name' => 'required|string|max:255',
    //         'company_name' => 'nullable|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . ($this->userId ?? 'NULL'),
    //         'phone' => 'nullable|string|max:255',
    //         'location' => 'nullable|string|max:255',
    //         'about' => 'nullable|string',
    //     ]);

    //     $user = User::updateOrCreate(
    //         ['id' => $this->userId],
    //         [
    //             'name' => $this->name,
    //             'company_name' => $this->company_name,
    //             'email' => $this->email,
    //             'location' => $this->location,
    //             'phone' => $this->phone,
    //             'about' => $this->about
    //         ]
    //     );

    //     // Validate the address data
    //     $this->validate([
    //         'address_type' => 'required|string|max:255',
    //         'address' => 'required|string|max:255',
    //         'landmark' => 'nullable|string|max:255',
    //         'city' => 'required|string|max:255',
    //         'state' => 'required|string|max:255',
    //         'country' => 'required|string|max:255',
    //         'zip_code' => 'required|string|max:10',
    //     ]);

    //     // Save or update the address
    //     UserAddress::updateOrCreate(
    //         ['user_id' => $user->id],
    //         [
    //             'address_type' => $this->address_type,
    //             'address' => $this->address,
    //             'landmark' => $this->landmark,
    //             'city' => $this->city,
    //             'state' => $this->state,
    //             'country' => $this->country,
    //             'zip_code' => $this->zip_code
    //         ]
    //     );

    //     session()->flash('message', 'User and Address saved successfully.');

    //     // Reset form fields after saving
    //     $this->resetFields();
    // }

   
    public function save()
    {
        // Validate data
        // $validatedData = $this->validate([
            
        // ]);
        $this->validate();
        // Store user data

        if ($this->gst_certificate_image) {
            // Generate a unique filename using the current timestamp
            $timestamp = now()->timestamp;
            $imageName = $timestamp . '-' . $this->gst_certificate_image->getClientOriginalName();

            // Store the image in the public folder
            $imagePath =  $image->storeAs('hotel_images', $uniqueFilename, 'public');
            $data['gst_certificate_image'] = $imagePath;
        }
        $user = User::create([
            'name' => $this->name,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp_no' => $this->whatsapp_no,
            'gst_number' => $this->gst_number,
            'credit_limit' => $this->credit_limit,
            'credit_days' => $this->credit_days,
            'gst_certificate_image' =>$data['gst_certificate_image'] 
            // 'password' => bcrypt('password123'), // Default password
        ]);

        // Store user address
        UserAddress::create([
            'user_id' => $user->id,
            'address_type' => $this->address_type,
            'address' => $this->address,
            'landmark' => $this->landmark,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'zip_code' => $this->zip_code,
        ]);

        // Reset form and display success message
        $this->reset();
        session()->flash('success', 'Customer information saved successfully!');
        return redirect()->route('customers.index');
        // $this->resetFields();
    }
    // public function deleteAddress()
    // {
    //     if ($this->address_id) {
    //         $address = UserAddress::find($this->address_id);
    //         $address->delete();
    //         session()->flash('message', 'Address deleted successfully.');
    //         $this->resetFields();
    //     }
    // }

    // public function resetFields()
    // {
    //     $this->name = '';
    //     $this->company_name = '';
    //     $this->email = '';
    //     $this->location = '';
    //     $this->phone = '';
    //     $this->about = '';
    //     $this->address_type = '';
    //     $this->address = '';
    //     $this->landmark = '';
    //     $this->city = '';
    //     $this->state = '';
    //     $this->country = '';
    //     $this->zip_code = '';
    // }

    public function render()
    {
        return view('livewire.user-address-form');
    }
}
