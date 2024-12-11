<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithFileUploads;


class CustomerEdit extends Component
{
    use WithFileUploads;

    public $customerId;
    public $name, $company_name, $email, $phone, $whatsapp_no, $gst_number;
    public $credit_limit, $credit_days, $address_type, $address, $landmark, $city, $state, $country, $zip_code;
    public $gst_certificate_image; // For storing the uploaded image
    public $existingGstCertificateImage; 
    // Load customer data
    public function mount($id)
    {
        $customer = User::findOrFail($id);
        $customer = User::with('address')->findOrFail($id);

        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->company_name = $customer->company_name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->whatsapp_no = $customer->whatsapp_no;
        $this->gst_number = $customer->gst_number;
        $this->credit_limit = $customer->credit_limit;
        $this->credit_days = $customer->credit_days;

        // Check if the user has an associated address
        if ($customer->address) {
            $this->address_type = $customer->address->address_type;
            $this->address = $customer->address->address;
            $this->landmark = $customer->address->landmark;
            $this->city = $customer->address->city;
            $this->state = $customer->address->state;
            $this->country = $customer->address->country;
            $this->zip_code = $customer->address->zip_code;
        }

        $this->existingGstCertificateImage = $customer->gst_certificate_image;
        // dd($this->existingGstCertificateImage);
    }

    // Update customer data
    public function updateCustomer()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'whatsapp_no' => 'nullable|string|max:15',
            'gst_number' => 'nullable|string|max:20',
            'credit_limit' => 'nullable|numeric',
            'credit_days' => 'nullable|numeric',
            'gst_certificate_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        $customer = User::findOrFail($this->customerId);
        
        $data = [
            'name' => $this->name,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp_no' => $this->whatsapp_no,
            'gst_number' => $this->gst_number,
            'credit_limit' => $this->credit_limit,
            'credit_days' => $this->credit_days,
        ];

        if ($this->gst_certificate_image) {
            $timestamp = now()->timestamp;
            $imageName = $timestamp . '-' . $this->gst_certificate_image->getClientOriginalName();
            $imagePath = $this->gst_certificate_image->storeAs('public/gst_certificates', $imageName);
            $data['gst_certificate_image'] = str_replace('public/', '', $imagePath);
        }

        $customer->update($data);

        if ($customer->address) {
            $customer->address->update([
                'address_type' => $this->address_type,
                'address' => $this->address,
                'landmark' => $this->landmark,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'zip_code' => $this->zip_code,
            ]);
        } else {
            $customer->address()->create([
                'address_type' => $this->address_type,
                'address' => $this->address,
                'landmark' => $this->landmark,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'zip_code' => $this->zip_code,
            ]);
        }

        session()->flash('success', 'Customer and address updated successfully.');

        // Redirect back to the same page
        return redirect()->route('admin.customers.edit', ['id' => $this->customerId]);
    }

   
    



    public function render()
    {
        return view('livewire.customer-edit');
    }
}
