<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Measurement;

class OrderNew extends Component
{
    public $searchTerm = '';
    public $searchResults = [];
    public $errorClass = [];
    public $errorMessage = [];
    public $activeTab = 2;
    public $FetchProduct = 1;

    public $customers = null;
    public $is_wa_same, $name, $company_name, $email, $dob, $customer_id, $whatsapp_no, $phone;
    public $billing_address,$billing_landmark,$billing_city,$billing_state,$billing_country,$billing_pin;

    public $is_billing_shipping_same;

    public $shipping_address,$shipping_landmark,$shipping_city,$shipping_state,$shipping_country,$shipping_pin;

    //  product 
    public $categories,$subCategories = [], $products = [], $measurements = [];
    public $selectedCategory = null, $selectedSubCategory = null,$searchproduct, $product_id =null;


    public function mount(){
        $this->customers = User::where('user_type', 1)->where('status', 1)->orderBy('name', 'ASC')->get();
        $this->categories = Category::where('status',1)->orderBy('title','ASC')->get();
     
    }
    public function FindCustomer($term)
    {
        $this->searchTerm = $term;

        if (!empty($this->searchTerm)) {
            $this->searchResults = User::where('user_type', 1)
                ->where('status', 1)
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('whatsapp_no', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                })
                ->take(20)
                ->get();
        } else {
            $this->searchResults = [];
        }
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
    public function GetProduct($cat_val = null, $value=null){
        $this->FetchProduct = 2;
        $this->product_id = null;
        if($cat_val){
            $this->CatWiseSubCatProduct($cat_val);
        }elseif($value){
            $this->SubCatWiseProduct($value);
        }else{
            session()->flash('error', '🚨 Oops! Please select first category or subcategory.');
            return;
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

    public function selectCustomer($customerId)
    {
        $this->resetForm(); // Reset form to default values

        $customer = User::find($customerId);

        if ($customer) {
            // Populate customer details
            $this->name = $customer->name;
            $this->company_name = $customer->company_name;
            $this->email = $customer->email;
            $this->dob = $customer->dob;
            $this->phone = $customer->phone;
            $this->whatsapp_no = $customer->whatsapp_no;

            // Fetch billing address (address_type = 1)
            $billingAddress = $customer->address()->where('address_type', 1)->first();
            $this->populateAddress('billing', $billingAddress);

            // Fetch shipping address (address_type = 2)
            $shippingAddress = $customer->address()->where('address_type', 2)->first();
            $this->populateAddress('shipping', $shippingAddress);
        }

        // Clear search results after selection
        $this->searchResults = [];
        $this->searchTerm = '';
    }

    public function selectProduct($value, $id){
        $this->searchproduct = $value;
        $this->product_id = $id;
        $this->products = [];
        $this->measurements = Measurement::where('product_id', $this->product_id)->where('status', 1)->orderBy('position','ASC')->get();
        if (count($this->measurements) == 0) {
            session()->flash('measurements_error', '🚨 Oops! Measurement data not added for this product.');
            return;
        }
    }   
    /**
     * Populate address fields based on the address type (billing or shipping).
     *
     * @param string $type Address type: 'billing' or 'shipping'
     * @param mixed $address Address model instance or null
     */
    private function populateAddress($type, $address)
    {
        if ($address) {
            $this->{$type . '_address'} = $address->address;
            $this->{$type . '_landmark'} = $address->landmark;
            $this->{$type . '_city'} = $address->city;
            $this->{$type . '_state'} = $address->state;
            $this->{$type . '_country'} = $address->country;
            $this->{$type . '_pin'} = $address->zip_code;
        } else {
            $this->{$type . '_address'} = null;
            $this->{$type . '_landmark'} = null;
            $this->{$type . '_city'} = null;
            $this->{$type . '_state'} = null;
            $this->{$type . '_country'} = null;
            $this->{$type . '_pin'} = null;
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
        $this->TabChange($this->activeTab);
    }

    public function TabChange($value)
    {
        // Initialize or reset error classes and messages
        $this->errorClass = [];
        $this->errorMessage = [];
        if ($value== 1) {
            $this->activeTab = $value;
        }
        if ($value > 1) {
            // Validate Name
            if (empty($this->name)) {
                $this->errorClass['name'] = 'border-danger';
                $this->errorMessage['name'] = 'Please enter customer name';
            } else {
                $this->errorClass['name'] = null;
                $this->errorMessage['name'] = null;
            }
    
            // Validate Email
            if (empty($this->email)) {
                $this->errorClass['email'] = 'border-danger';
                $this->errorMessage['email'] = 'Please enter customer email';
            } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->errorClass['email'] = 'border-danger';
                $this->errorMessage['email'] = 'Please enter a valid email address';
            } else {
                $this->errorClass['email'] = null;
                $this->errorMessage['email'] = null;
            }
    
            // Validate Date of Birth
            if (empty($this->dob)) {
                $this->errorClass['dob'] = 'border-danger';
                $this->errorMessage['dob'] = 'Please enter customer date of birth';
            } else {
                $this->errorClass['dob'] = null;
                $this->errorMessage['dob'] = null;
            }
    
            // Validate Phone Number
            if (empty($this->phone)) {
                $this->errorClass['phone'] = 'border-danger';
                $this->errorMessage['phone'] = 'Please enter customer phone number';
            } elseif (strlen($this->phone) != env('VALIDATE_MOBILE', 12)) {
                $this->errorClass['phone'] = 'border-danger';
                $this->errorMessage['phone'] = 'Phone number must be ' . env('VALIDATE_MOBILE', 12) . ' digits long';
            } else {
                $this->errorClass['phone'] = null;
                $this->errorMessage['phone'] = null;
            }
    
            // Validate WhatsApp Number
            if (empty($this->whatsapp_no)) {
                $this->errorClass['whatsapp_no'] = 'border-danger';
                $this->errorMessage['whatsapp_no'] = 'Please enter WhatsApp number';
            } elseif (strlen($this->whatsapp_no) != env('VALIDATE_WHATSAPP', 12)) {
                $this->errorClass['whatsapp_no'] = 'border-danger';
                $this->errorMessage['whatsapp_no'] = 'WhatsApp number must be ' . env('VALIDATE_WHATSAPP', 12) . ' digits long';
            } else {
                $this->errorClass['whatsapp_no'] = null;
                $this->errorMessage['whatsapp_no'] = null;
            }
    
            // Validate Billing Information
            if (empty($this->billing_address)) {
                $this->errorClass['billing_address'] = 'border-danger';
                $this->errorMessage['billing_address'] = 'Please enter billing address';
            } else {
                $this->errorClass['billing_address'] = null;
                $this->errorMessage['billing_address'] = null;
            }
    
            if (empty($this->billing_city)) {
                $this->errorClass['billing_city'] = 'border-danger';
                $this->errorMessage['billing_city'] = 'Please enter billing city';
            } else {
                $this->errorClass['billing_city'] = null;
                $this->errorMessage['billing_city'] = null;
            }
    
            if (empty($this->billing_state)) {
                $this->errorClass['billing_state'] = 'border-danger';
                $this->errorMessage['billing_state'] = 'Please enter billing state';
            } else {
                $this->errorClass['billing_state'] = null;
                $this->errorMessage['billing_state'] = null;
            }
    
            if (empty($this->billing_country)) {
                $this->errorClass['billing_country'] = 'border-danger';
                $this->errorMessage['billing_country'] = 'Please enter billing country';
            } else {
                $this->errorClass['billing_country'] = null;
                $this->errorMessage['billing_country'] = null;
            }
    
            if (empty($this->billing_pin)) {
                $this->errorClass['billing_pin'] = 'border-danger';
                $this->errorMessage['billing_pin'] = 'Please enter billing pin';
            } elseif (strlen($this->billing_pin) != env('VALIDATE_PIN', 6)) {  // Assuming pin should be 6 digits
                $this->errorClass['billing_pin'] = 'border-danger';
                $this->errorMessage['billing_pin'] = 'Billing pin must be '.env('VALIDATE_PIN', 6).' digits';
            } else {
                $this->errorClass['billing_pin'] = null;
                $this->errorMessage['billing_pin'] = null;
            }
    
            // Validate Shipping Information
            if (empty($this->shipping_address)) {
                $this->errorClass['shipping_address'] = 'border-danger';
                $this->errorMessage['shipping_address'] = 'Please enter shipping address';
            } else {
                $this->errorClass['shipping_address'] = null;
                $this->errorMessage['shipping_address'] = null;
            }
    
            if (empty($this->shipping_city)) {
                $this->errorClass['shipping_city'] = 'border-danger';
                $this->errorMessage['shipping_city'] = 'Please enter shipping city';
            } else {
                $this->errorClass['shipping_city'] = null;
                $this->errorMessage['shipping_city'] = null;
            }
    
            if (empty($this->shipping_state)) {
                $this->errorClass['shipping_state'] = 'border-danger';
                $this->errorMessage['shipping_state'] = 'Please enter shipping state';
            } else {
                $this->errorClass['shipping_state'] = null;
                $this->errorMessage['shipping_state'] = null;
            }
    
            if (empty($this->shipping_country)) {
                $this->errorClass['shipping_country'] = 'border-danger';
                $this->errorMessage['shipping_country'] = 'Please enter shipping country';
            } else {
                $this->errorClass['shipping_country'] = null;
                $this->errorMessage['shipping_country'] = null;
            }
    
            if (empty($this->shipping_pin)) {
                $this->errorClass['shipping_pin'] = 'border-danger';
                $this->errorMessage['shipping_pin'] = 'Please enter shipping pin';
            } elseif (strlen($this->shipping_pin) != env('VALIDATE_PIN', 6)) {  // Assuming pin should be 6 digits
                $this->errorClass['shipping_pin'] = 'border-danger';
                $this->errorMessage['shipping_pin'] = 'Shipping pin must be '.env('VALIDATE_PIN', 6).' digits';
            } else {
                $this->errorClass['shipping_pin'] = null;
                $this->errorMessage['shipping_pin'] = null;
            }
    
           
            // Check if both errorClass and errorMessage arrays are empty

            $errorClassNull = empty(array_filter($this->errorClass, function($val) {
                return !is_null($val);
            }));
            // If all values are null, set activeTab to the value passed
            if ($errorClassNull) {
                $this->activeTab = $value;
            }
             // Return the error classes and messages
            return [$this->errorClass, $this->errorMessage];
        }
       
    }
    public function FindPrduct($term){
        $this->searchTerm = $term;
        $this->product_id = null;

        if (!empty($this->searchTerm)) {
            $this->products = Product::where('status', 1)
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('product_code', 'like', '%' . $this->searchTerm . '%');
                })
                ->where('category_id', $this->selectedCategory)
                ->orWhere('sub_category_id', $this->selectedSubCategory)
                ->get();
        } else {
            $this->products = [];
        }
        if(count($this->products)){
            $this->FetchProduct = 2;
        }
    }

    
    public function render()
    {
        return view('livewire.order.order-new');
    }
}
