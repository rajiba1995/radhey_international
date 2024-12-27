<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Measurement;
use App\Models\Fabric;

class OrderEdit extends Component
{
    public $searchTerm = '';
    public $searchResults = [];
    public $errorClass = [];
    // public $collectionsType = [];
    public $collections = [];
    public $errorMessage = [];
    public $activeTab = 1;
    public $items = [];
    public $FetchProduct = 1;

    public $customers = null;
    public $orders = null;
    public $is_wa_same, $name, $company_name,$employee_rank, $email, $dob, $customer_id, $whatsapp_no, $phone;
    public $billing_address,$billing_landmark,$billing_city,$billing_state,$billing_country,$billing_pin;

    public $is_billing_shipping_same;

    public $shipping_address,$shipping_landmark,$shipping_city,$shipping_state,$shipping_country,$shipping_pin;

    //  product 
    public $categories,$subCategories = [], $products = [], $measurements = [];
    public $selectedCategory = null, $selectedSubCategory = null,$searchproduct, $product_id =null,$collection;
    public $paid_amount = 0;
    public $billing_amount = 0;
    public $remaining_amount = 0;
    public $payment_mode = null;

    // public $order;
    // public $order;


    // public function mount($id)
    // {

    //     $this->orders = Order::findOrFail($id); // Fetch the order by ID
       
            
    //     // Split the address and assign to the properties
    //     $billingAddress = explode(',', $this->orders->billing_address);

    //     // Assuming the address is saved in the format: street, landmark, city, state, country - pin
    //     if (count($billingAddress) >= 5) {
    //         $this->billing_address = trim($billingAddress[0]); // Street Address
    //         $this->billing_landmark = trim($billingAddress[1]); // Landmark
    //         $this->billing_city = trim($billingAddress[2]); // City
    //         $this->billing_state = trim($billingAddress[3]); // State
    //         $this->billing_country = trim($billingAddress[4]); // Country and PIN code

    //         // Extract pin code from the country field (assuming it's at the end)
    //         $countryAndPin = explode('-', $this->billing_country);
    //         if (count($countryAndPin) > 1) {
    //             $this->billing_country = trim($countryAndPin[0]);
    //             $this->billing_pin = trim($countryAndPin[1]);
    //         }
    //     }

    //     // Split the address and assign to the properties
    //     $shippingAddress = explode(',', $this->orders->shipping_address);

    //     // Assuming the address is saved in the format: street, landmark, city, state, country - pin
    //     if (count($shippingAddress) >= 5) {
    //         $this->shipping_address = trim($shippingAddress[0]); // Street Address
    //         $this->shipping_landmark = trim($shippingAddress[1]); // Landmark
    //         $this->shipping_city = trim($shippingAddress[2]); // City
    //         $this->shipping_state = trim($shippingAddress[3]); // State
    //         $this->shipping_country = trim($shippingAddress[4]); // Country and PIN code

    //         // Extract pin code from the country field (assuming it's at the end)
    //         $countryAndPin = explode('-', $this->shipping_country);
    //         if (count($countryAndPin) > 1) {
    //             $this->shipping_country = trim($countryAndPin[0]);
    //             $this->shipping_pin = trim($countryAndPin[1]);
    //         }
    //     }


    //     $this->customer_id = $this->orders->customer_id;
    //     $this->name = $this->orders->customer_name;
    //     $this->company_name = $this->orders->customer->company_name;
    //     $this->employee_rank = $this->orders->customer->employee_rank;
    //     $this->email = $this->orders->customer_email;
    //     $this->dob = $this->orders->customer->dob;
    //     $this->phone = $this->orders->customer->phone;
    //     $this->whatsapp_no = $this->orders->customer->whatsapp_no;


    //     $this->customers = User::where('user_type', 1)->where('status', 1)->orderBy('name', 'ASC')->get();
    //     $this->categories = Category::where('status', 1)->orderBy('title', 'ASC')->get();
    //     $this->collections = Collection::orderBy('title', 'ASC')->get();
    //     $this->addItem();
    // }

    public function mount($id)
    {
        $this->orders = Order::with(['items.measurements'])->findOrFail($id); // Fetch the order by ID
        // Collect measurements related to the order items
        $this->measurements = [];
        foreach ($this->orders->items as $item) {
            foreach ($item->measurements as $measurement) {
                $this->measurements[] = $measurement;
            }
        }

        if ($this->orders) {
            $this->customer_id = $this->orders->customer_id;
            $this->name = $this->orders->customer_name;
            $this->email = $this->orders->customer_email;
            $this->dob = $this->orders->customer->dob;
            $this->billing_address = $this->orders->billing_address;
            $this->shipping_address = $this->orders->shipping_address;
            $this->paid_amount = $this->orders->paid_amount;
            $this->payment_mode = $this->orders->payment_mode;
            $this->phone = $this->orders->customer->phone;
            $this->whatsapp_no = $this->orders->customer->whatsapp_no;

            $this->items = $this->orders->items->map(function ($item) {
                // dd($item->collection);
                return [
                    'product_id' => $item->product_id,
                    'searchproduct' => $item->product_name,
                    'price' => $item->price,
                    'collection' => $item->collection,
                    'category' => $item->category,
                    'sub_category' => $item->sub_category,
                    'selected_fabric' => $item->fabrics,
                    'get_measurements' => $item->measurements->mapWithKeys(function ($measurement) {
                        return [$measurement->id => ['value' => $measurement->measurement_value]];
                    })->toArray(),
                ];
            })->toArray();
        }

        // Split the address and assign to the properties
        $billingAddress = explode(',', $this->orders->billing_address);

        // Assuming the address is saved in the format: street, landmark, city, state, country - pin
        if (count($billingAddress) >= 5) {
            $this->billing_address = trim($billingAddress[0]); // Street Address
            $this->billing_landmark = trim($billingAddress[1]); // Landmark
            $this->billing_city = trim($billingAddress[2]); // City
            $this->billing_state = trim($billingAddress[3]); // State
            $this->billing_country = trim($billingAddress[4]); // Country and PIN code

            // Extract pin code from the country field (assuming it's at the end)
            $countryAndPin = explode('-', $this->billing_country);
            if (count($countryAndPin) > 1) {
                $this->billing_country = trim($countryAndPin[0]);
                $this->billing_pin = trim($countryAndPin[1]);
            }
        }

        // Split the address and assign to the properties
        $shippingAddress = explode(',', $this->orders->shipping_address);

        // Assuming the address is saved in the format: street, landmark, city, state, country - pin
        if (count($shippingAddress) >= 5) {
            $this->shipping_address = trim($shippingAddress[0]); // Street Address
            $this->shipping_landmark = trim($shippingAddress[1]); // Landmark
            $this->shipping_city = trim($shippingAddress[2]); // City
            $this->shipping_state = trim($shippingAddress[3]); // State
            $this->shipping_country = trim($shippingAddress[4]); // Country and PIN code

            // Extract pin code from the country field (assuming it's at the end)
            $countryAndPin = explode('-', $this->shipping_country);
            if (count($countryAndPin) > 1) {
                $this->shipping_country = trim($countryAndPin[0]);
                $this->shipping_pin = trim($countryAndPin[1]);
            }
        }

        // $this->customer_id = $this->orders->customer_id;
        // $this->name = $this->orders->customer_name;
        // $this->company_name = $this->orders->customer->company_name;
        // $this->employee_rank = $this->orders->customer->employee_rank;
        // $this->email = $this->orders->customer_email;
        // $this->dob = $this->orders->customer->dob;
        // $this->phone = $this->orders->customer->phone;
        // $this->whatsapp_no = $this->orders->customer->whatsapp_no;
       

        $this->customers = User::where('user_type', 1)->where('status', 1)->orderBy('name', 'ASC')->get();
        $this->categories = Category::where('status', 1)->orderBy('title', 'ASC')->get();
        $this->collections = Collection::orderBy('title', 'ASC')->get();
        $this->addItem();
    }


    public function addItem()
    {
        $this->items[] = [
           
            'collection' => '',
            'category' => '',
            'sub_category' => '',
            'searchproduct' => '',
            'selected_fabric' => null,
            'measurements' => [],
            'products' => [],
            'product_id' => null,
            'price' => '', // Ensure price is initialized to an empty string, not null.
        ];
        // $this->validate();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->updateBillingAmount();  // Update billing amount after checking price
    }

    public function updateBillingAmount()
    {
        // Recalculate the total billing amount
        $this->billing_amount = array_sum(array_column($this->items, 'price'));
        $this->paid_amount = $this->billing_amount;
        $this->GetRemainingAmount($this->paid_amount);
        return;
    }

    public function GetRemainingAmount($paid_amount)
    {
       // Remove leading zeros if present in the paid amount
        
        // Ensure the values are numeric before performing subtraction
        $billingAmount = (float) $this->billing_amount;
        $paidAmount = (float) $paid_amount;
        $paidAmount = ltrim($paidAmount, '0');
        if ($billingAmount > 0) {
            if(empty($paid_amount)){
                $this->paid_amount = 0;
                $this->remaining_amount = $billingAmount;
                return;
            }
            $this->paid_amount = $paidAmount;
            $this->remaining_amount = $billingAmount - $this->paid_amount;
        
            // Check if the remaining amount is negative
            if ($this->remaining_amount < 0) {
                $this->remaining_amount = 0;
                $this->paid_amount = $this->billing_amount;
                session()->flash('errorAmount', '🚨 The paid amount exceeds the billing amount.');
            }
        } else {
            $this->paid_amount = 0;
           
            session()->flash('errorAmount', '🚨 Please add item amount first.');
        }
    }

    public function GetCategory($value,$index)
    {
        // Reset products, and product_id for the selected item
        $this->items[$index]['product_id'] = null;
        $this->items[$index]['measurements'] = [];
        $this->items[$index]['fabrics'] = [];
      
            // Fetch categories and products based on the selected collection 
            $this->items[$index]['categories'] = Category::orderBy('title', 'ASC')->where('collection_id', $value)->get();
            $this->items[$index]['products'] = Product::orderBy('name', 'ASC')->where('collection_id', $value)->get();
       
    }


    public function selectProduct($index, $name, $id)
    {
        $this->items[$index]['searchproduct'] = $name;
        $this->items[$index]['product_id'] = $id;
        $this->items[$index]['products'] = [];
        $this->items[$index]['measurements'] = Measurement::where('product_id', $id)->where('status', 1)->orderBy('position','ASC')->get();
        $this->items[$index]['fabrics'] = Fabric::where('product_id', $id)->where('status', 1)->get();
        
        session()->forget('measurements_error.' . $index);
        if (count($this->items[$index]['measurements']) == 0) {
            session()->flash('measurements_error.' . $index, '🚨 Oops! Measurement data not added for this product.');
            return;
        }
    }

    public function CategoryWiseProduct($categoryId, $index)
    {
        // Reset products for the selected item
        $this->items[$index]['products'] = [];
        $this->items[$index]['product_id'] = null;

        if ($categoryId) {
            // Fetch products based on the selected category and collection
            $this->items[$index]['products'] = Product::where('category_id', $categoryId)
                ->where('collection_id', $this->items[$index]['collection']) // Ensure the selected collection is considered
                ->get();
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
            } elseif (!preg_match('/^\d{' . env('VALIDATE_MOBILE', 8) . ',}$/', $this->phone)) {
                $this->errorClass['phone'] = 'border-danger';
                $this->errorMessage['phone'] = 'Phone number must be ' . env('VALIDATE_MOBILE', 8) . ' or more digits long';
            } else {
                $this->errorClass['phone'] = null;
                $this->errorMessage['phone'] = null;
            }

            // Validate WhatsApp Number
           if (empty($this->whatsapp_no)) {
                $this->errorClass['whatsapp_no'] = 'border-danger';
                $this->errorMessage['whatsapp_no'] = 'Please enter WhatsApp number';
            } elseif (!preg_match('/^\d{' . env('VALIDATE_WHATSAPP', 8) . ',}$/', $this->whatsapp_no)) {
                $this->errorClass['whatsapp_no'] = 'border-danger';
                $this->errorMessage['whatsapp_no'] = 'WhatsApp number must be ' . env('VALIDATE_WHATSAPP', 8) . ' or more digits long';
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

    public function checkproductPrice($value, $index)
    {
        // Remove any non-numeric characters except for the decimal point
        $formattedValue = preg_replace('/[^0-9.]/', '', $value);

        // Check if the value is numeric
        if (is_numeric($formattedValue)) {
            // Format the value to two decimal places if it's a valid number
            // $this->items[$index]['price'] = number_format((float)$formattedValue, 2, '.', '');
            session()->forget('errorPrice.' . $index); // Clear any previous error message
        } else {
            // If the value is invalid, reset the price and show an error message
            $this->items[$index]['price'] = 0;
            session()->flash('errorPrice.' . $index, '🚨 Please enter a valid price.');
        }
        $this->updateBillingAmount();  // Update billing amount after checking price
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

    public function render()
    {
        // dd($this->order);
        return view('livewire.order.order-edit' ,[
            'order' => $this->orders
        ]);
    }
}
