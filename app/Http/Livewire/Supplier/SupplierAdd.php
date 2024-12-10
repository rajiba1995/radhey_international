<?php

namespace App\Http\Livewire\Supplier;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupplierAdd extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $mobile;
    public $whatsapp_no;
    public $is_wa_same = 0;
    public $billing_address;
    public $billing_landmark;
    public $billing_state;
    public $billing_city;
    public $billing_pin;
    public $billing_country;
    public $shipping_address;
    public $shipping_landmark;
    public $shipping_state;
    public $shipping_city;
    public $shipping_pin;
    public $shipping_country;
    public $is_billing_shipping_same = false; // Checkbox for Shipping same as Billing
    public $gst_number;
    public $gst_file;
    public $credit_limit;
    public $credit_days;
    public $status;


    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:suppliers,email',
        'mobile' => 'required|string|max:10',
        // 'is_wa_same' => 'boolean',
        'whatsapp_no' => 'nullable|string|max:10',
        'billing_address' => 'required|string|max:255',
        'billing_landmark' => 'nullable|string|max:255',
        'billing_state' => 'nullable|string|max:255',
        'billing_city' => 'nullable|string|max:255',
        'billing_pin' => 'nullable|string|max:10',
        'billing_country' => 'nullable|string|max:255',
        'shipping_address' => 'nullable|string|max:255',
        'shipping_landmark' => 'nullable|string|max:255',
        'shipping_state' => 'nullable|string|max:255',
        'shipping_city' => 'nullable|string|max:255',
        'shipping_pin' => 'nullable|string|max:10',
        'shipping_country' => 'nullable|string|max:255',
        // 'is_billing_shipping_same' => 'boolean',
        'gst_number' => 'nullable|string|max:255',
        'gst_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:1024',
        'credit_limit' => 'nullable|numeric',
        'credit_days' => 'nullable|numeric',
        // 'status' => 'required|boolean',
    ];
    public function updated($propertyName)
    {
        
        // logger('Updated property: ' . $propertyName);
        // logger('Current State:', $this->toArray());
        // dd($this->$propertyName, $this->is_wa_same, $this->is_billing_shipping_same);
        if ($propertyName === 'is_wa_same') {
            if ($this->is_wa_same) {
                $this->whatsapp_no = $this->mobile; // Copy mobile to WhatsApp number
            } else {
                $this->whatsapp_no = null; // Clear WhatsApp number if unchecked
            }
        }
    
        if ($propertyName === 'is_billing_shipping_same') {
            if ($this->is_billing_shipping_same) {
                $this->syncBillingToShipping(); // Copy billing to shipping
            } else {
                $this->clearShipping(); // Clear shipping if unchecked
            }
        }
    }
    
    private function syncBillingToShipping()
    {
        $this->shipping_address = $this->billing_address;
        $this->shipping_landmark = $this->billing_landmark;
        $this->shipping_state = $this->billing_state;
        $this->shipping_city = $this->billing_city;
        $this->shipping_pin = $this->billing_pin;
        $this->shipping_country = $this->billing_country;
    }
    
    private function clearShipping()
    {
        $this->shipping_address = null;
        $this->shipping_landmark = null;
        $this->shipping_state = null;
        $this->shipping_city = null;
        $this->shipping_pin = null;
        $this->shipping_country = null;
    }
    
    public function save()
    {
        $this->validate();

       

        if ($this->gst_file) {
            $timestamp = now()->timestamp;
            $imageName = $timestamp . '-' . $this->gst_file->getClientOriginalName();
        
            $imagePath = $this->gst_file->storeAs('gst_file', $imageName, 'public');
        
            $data['gst_file'] = $imagePath;
        }

        Supplier::create([
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            // 'is_wa_same' => $this->is_wa_same,
            'whatsapp_no' => $this->whatsapp_no,
            'billing_address' => $this->billing_address,
            'billing_landmark' => $this->billing_landmark,
            'billing_state' => $this->billing_state,
            'billing_city' => $this->billing_city,
            'billing_pin' => $this->billing_pin,
            'billing_country' => $this->billing_country,
            'shipping_address' => $this->is_billing_shipping_same ? $this->billing_address : $this->shipping_address,
            'shipping_landmark' => $this->is_billing_shipping_same ? $this->billing_landmark : $this->shipping_landmark,
            'shipping_state' => $this->is_billing_shipping_same ? $this->billing_state : $this->shipping_state,
            'shipping_city' => $this->is_billing_shipping_same ? $this->billing_city : $this->shipping_city,
            'shipping_pin' => $this->is_billing_shipping_same ? $this->billing_pin : $this->shipping_pin,
            'shipping_country' => $this->is_billing_shipping_same ? $this->billing_country : $this->shipping_country,
            // 'is_billing_shipping_same' => $this->is_billing_shipping_same,
            'gst_number' => $this->gst_number,
            'gst_file' => $data['gst_file'],
            'credit_limit' => $this->credit_limit,
            'credit_days' => $this->credit_days,
            // 'status' => $this->status,
        ]);

        session()->flash('message', 'Supplier added successfully!');
        return redirect()->route('suppliers.index');
    }

    public function SameAsMobile()
    {   
        if ($this->is_wa_same == 0) {
            $this->whatsapp_no = $this->mobile;
            $this->is_wa_same = 1;
        } else {
            // When the checkbox is unchecked, clear WhatsApp number
            $this->whatsapp_no = '';
            $this->is_wa_same = 0;
        }
    }

    public function SameAsBillingAddress()
    {   
        if ($this->is_billing_shipping_same == 0) {
            $this->syncBillingToShipping();
            $this->is_billing_shipping_same = 1;
        } else {
            // When the checkbox is unchecked, clear shipping address
            $this->clearShipping();
            $this->is_billing_shipping_same = 0;
        }
    }

    public function render()
    {
        return view('livewire.supplier.supplier-add');
    }
}
