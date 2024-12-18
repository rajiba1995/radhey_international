<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;

class OrderNew extends Component
{
    public $customers = null;
    public $is_wa_same, $whatsapp_no, $phone;
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
    // New properties to store customer data
    public $name, $company_name, $email, $dob, $customer_id;
    //  product 
    public $categories,$subCategories = [], $products = [];
    public $selectedCategory,$selectedSubCategory;

    public function mount(){
        $this->customers = User::where('user_type', 1)->where('status', 1)->orderBy('name', 'ASC')->get();
        $this->categories = Category::where('status',1)->orderBy('title','ASC')->get();
    }

    public function CatWiseSubCatProduct($value){
        
        $this->selectedCategory = $value;

        if ($this->selectedCategory) {
            $this->subCategories = SubCategory::where('category_id', $this->selectedCategory)->get();
            $this->products = Product::where('category_id', $this->selectedCategory)->get();
        } else {
            // Reset subcategories and products if no category is selected
            $this->subCategories = [];
            $this->products = [];
        }
    }

        public function SubCatWiseProduct($value)
    {
        $this->selectedSubCategory = $value;

        if ($this->selectedSubCategory) {
            $this->products = Product::where('category_id', $this->selectedCategory)
            ->where('sub_category_id', $this->selectedSubCategory)
            ->get();
        } else{
            $this->products = [];
        }
    }

    public function resetForm()
    {
        // Reset all the form properties
        $this->reset([
            'name',
            'company_name',
            'email',
            'dob',
            'phone',
            'whatsapp_no',
           
        ]);
    }

    public function selectCustomer($customerId){
        $this->resetForm();
        $customer = User::find($customerId);
        if($customer){
            // Fill the form fields with the customer data
            $this->name = $customer->name;
            $this->company_name = $customer->company_name;
            $this->email = $customer->email ;
            $this->dob = $customer->dob ;
            $this->phone = $customer->phone;
            $this->whatsapp_no = $customer->whatsapp_no ;
        // Fetch billing address (address_type = 'billing')
            $billing_address = $customer->address()->where('address_type',1)->first();
            if($billing_address){
                $this->billing_address =  $billing_address->address;
                $this->billing_landmark =  $billing_address->landmark;
                $this->billing_city =  $billing_address->city;
                $this->billing_state =  $billing_address->state;
                $this->billing_country =  $billing_address->country;
                $this->billing_pin =  $billing_address->zip_code;
            }
         // Fetch shipping address (address_type = 'shipping')
         $shipping_address = $customer->address()->where('address_type',2)->first();
            if($shipping_address){
                $this->shipping_address = $shipping_address->address;
                $this->shipping_landmark = $shipping_address->landmark;
                $this->shipping_city = $shipping_address->city;
                $this->shipping_state = $shipping_address->state;
                $this->shipping_country = $shipping_address->country;
                $this->shipping_pin = $shipping_address->zip_code;
            }
        }
    }

    public function SameAsMobile(){
        if($this->is_wa_same == 0){
            $this->whatsapp_no = $this->phone;
            $this->is_wa_same = 1;
        }else{
            $this->whatsapp_no = '';
            $this->is_wa_same = 0;
        }
    }
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
    public function render()
    {
        return view('livewire.order.order-new');
    }
}
