<?php

namespace App\Http\Livewire\Supplier;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupplierAdd extends Component
{
    use WithFileUploads;

    public $name, $email, $mobile, $is_wa_same, $whatsapp_no;
    public $billing_address, $billing_landmark, $billing_state, $billing_city, $billing_pin, $billing_country;
    public $shipping_address, $shipping_landmark, $shipping_state, $shipping_city, $shipping_pin, $shipping_country;
    public $is_billing_shipping_same, $gst_number, $gst_file, $credit_limit, $credit_days, $status;

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

    public function save()
    {
        $this->validate();

        $gstFilePath = null;
        if ($this->gst_file) {
            $gstFilePath = $this->gst_file->store('gst_files');
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
            'gst_file' => $gstFilePath,
            'credit_limit' => $this->credit_limit,
            'credit_days' => $this->credit_days,
            // 'status' => $this->status,
        ]);

        session()->flash('message', 'Supplier added successfully!');
        return redirect()->route('suppliers.index');
    }

    public function render()
    {
        return view('livewire.supplier.supplier-add');
    }
}
