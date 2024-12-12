<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserAddress;
use Livewire\WithFileUploads;

class CustomerEdit extends Component
{
    use WithFileUploads;

    public $id, $name, $company_name, $email, $phone, $whatsapp_no, $is_wa_same, $gst_number, $credit_limit, $credit_days, $gst_certificate_image, $image, $verified_video;
    public $billing_address, $billing_landmark, $billing_city, $billing_state, $billing_country, $billing_pin;
    public $shipping_address, $shipping_landmark, $shipping_city, $shipping_state, $shipping_country, $shipping_pin;
    public $is_billing_shipping_same;
    public $tempImageUrl;

    public function mount($id)
    {
        if ($id) {
            $user = User::find($id);
            // Load user data into the form fields
            $this->name = $user->name;
            $this->company_name = $user->company_name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->whatsapp_no = $user->whatsapp_no;
            $this->gst_number = $user->gst_number;
            $this->credit_limit = $user->credit_limit;
            $this->credit_days = $user->credit_days;

            // Load user media (images, videos, etc.)
            $this->image = $user->profile_image ? asset('storage/' . $user->profile_image) : null;
            $this->verified_video = $this->verified_video ? asset('storage/' . $this->verified_video) : null;
            $this->gst_certificate_image = $user->gst_certificate_image ? asset('storage/' . $user->gst_certificate_image) : null;

            // Load address data
            $billingAddress = $user->address()->where('address_type', 1)->first();
            if ($billingAddress) {
                $this->billing_address = $billingAddress->address;
                $this->billing_landmark = $billingAddress->landmark;
                $this->billing_city = $billingAddress->city;
                $this->billing_state = $billingAddress->state;
                $this->billing_country = $billingAddress->country;
                $this->billing_pin = $billingAddress->zip_code;
            }

            $shippingAddress = $user->address()->where('address_type', 2)->first();
            if ($shippingAddress) {
                $this->shipping_address = $shippingAddress->address;
                $this->shipping_landmark = $shippingAddress->landmark;
                $this->shipping_city = $shippingAddress->city;
                $this->shipping_state = $shippingAddress->state;
                $this->shipping_country = $shippingAddress->country;
                $this->shipping_pin = $shippingAddress->zip_code;
            }

            // Check if billing and shipping address are the same
            $this->is_billing_shipping_same = $billingAddress && $shippingAddress && $billingAddress->address == $shippingAddress->address;
        }
    }

    public function rules()
    {
        // Base rules
        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'nullable',
            'verified_video' => 'nullable|mimes:mp4,mov,avi,wmv',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email' . ($this->id ? '|unique:users,email,' . $this->id : ''),
            'phone' => 'required|string|max:10',
            'whatsapp_no' => 'required|string|max:10',
            'gst_number' => 'nullable|string|max:15',
            'credit_limit' => 'nullable|numeric',
            'credit_days' => 'nullable|integer',
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
            $rules['shipping_address'] = 'nullable|string';
            $rules['shipping_landmark'] = 'nullable|string';
            $rules['shipping_city'] = 'nullable|string';
            $rules['shipping_state'] = 'nullable|string';
            $rules['shipping_country'] = 'nullable|string';
            $rules['shipping_pin'] = 'nullable|string';
        }

        return $rules;
    }

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
            // Ensure that the file is actually an instance of UploadedFile
            if ($this->verified_video instanceof \Illuminate\Http\UploadedFile) {
                $timestamp = now()->timestamp;
                $videoName = $timestamp . '.' . $this->verified_video->getClientOriginalExtension();
                // Store the video file in the 'public' disk and return the file path
                $path = $this->verified_video->storeAs('profile_video', $videoName, 'public');
                return $path;
            }
        }
        return null;
    }
    

    private function uploadGSTCertificate()
    {
        if ($this->gst_certificate_image) {
            $timestamp = now()->timestamp;
            $imageName = $timestamp . '-' . $this->gst_certificate_image->getClientOriginalExtension();
            return $this->gst_certificate_image->storeAs('gst_certificate_image', $imageName, 'public');
        }
        return null;
    }

    public function updatedImage()
    {
        if ($this->image) {
            $this->tempImageUrl = $this->image->temporaryUrl();
        }
    }

    public function update()
    {
        $this->validate();

        // If updating, find the existing user
        if ($this->id) {
            $user = User::find($this->id);
        } else {
            $user = new User();
        }

        // Handle file uploads
        $imagePath = $this->uploadImage();
        $verifiedVideoPath = $this->uploadVideo();
        $gstCertificatePath = $this->uploadGSTCertificate();

        // Update or create user data
        $user->name = $this->name;
        $user->profile_image = $imagePath ? $imagePath : $user->profile_image;
        $user->verified_video = $verifiedVideoPath ? $verifiedVideoPath : $user->verified_video;
        $user->company_name = $this->company_name;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->whatsapp_no = $this->whatsapp_no;
        $user->gst_number = $this->gst_number;
        $user->credit_limit = $this->credit_limit;
        $user->credit_days = $this->credit_days;
        $user->gst_certificate_image = $gstCertificatePath ? $gstCertificatePath : $user->gst_certificate_image;
        $user->save();

        // Store addresses
        $this->storeAddress($user->id, 1, $this->billing_address, $this->billing_landmark, $this->billing_city, $this->billing_state, $this->billing_country, $this->billing_pin);

        // Handle shipping address
        if (!$this->is_billing_shipping_same) {
            $this->storeAddress($user->id, 2, $this->shipping_address, $this->shipping_landmark, $this->shipping_city, $this->shipping_state, $this->shipping_country, $this->shipping_pin);
        } else {
            $this->storeAddress($user->id, 2, $this->billing_address, $this->billing_landmark, $this->billing_city, $this->billing_state, $this->billing_country, $this->billing_pin);
        }

        session()->flash('success', 'Customer information saved successfully!');
        return redirect()->route('customers.index');
    }

    private function storeAddress($userId, $addressType, $address, $landmark, $city, $state, $country, $zipCode)
    {
        UserAddress::updateOrCreate(
            ['user_id' => $userId, 'address_type' => $addressType],
            ['address' => $address, 'landmark' => $landmark, 'city' => $city, 'state' => $state, 'country' => $country, 'zip_code' => $zipCode]
        );
    }

    public function render()
    {
        return view('livewire.customer-edit');
    }
}
