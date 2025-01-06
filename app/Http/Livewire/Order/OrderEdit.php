<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Measurement;
use App\Models\OrderMeasurement;
use App\Models\Fabric;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

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
    public $orders;
    public $is_wa_same, $name, $company_name,$employee_rank, $email, $dob, $customer_id, $whatsapp_no, $phone;
    public $order_number, $billing_address,$billing_landmark,$billing_city,$billing_state,$billing_country,$billing_pin;

    public $is_billing_shipping_same;

    public $shipping_address,$shipping_landmark,$shipping_city,$shipping_state,$shipping_country,$shipping_pin;

    //  product 
    public $categories,$subCategories = [], $products = [], $measurements = [];
    public $selectedCategory = null, $selectedSubCategory = null,$searchproduct, $product_id =null,$collection;
    public $paid_amount = 0;
    public $billing_amount = 0;
    public $remaining_amount = 0;
    public $payment_mode = null;


    

    public function mount($id)
    {
        $this->orders = Order::with(['items.measurements'])->findOrFail($id); // Fetch the order by ID

        if ($this->orders) {
            $this->order_number = $this->orders->order_number;
            $this->customer_id = $this->orders->customer_id;
            $this->name = $this->orders->customer_name;
            $this->email = $this->orders->customer_email;
            $this->dob = $this->orders->customer->dob;
            $this->billing_address = $this->orders->billing_address;
            $this->shipping_address = $this->orders->shipping_address;
            $this->phone = $this->orders->customer->phone;
            $this->whatsapp_no = $this->orders->customer->whatsapp_no;

            $this->items = $this->orders->items->map(function ($item) {
                $selected_titles = OrderMeasurement::where('order_item_id', $item->id)->pluck('measurement_name')->toArray();
                $selected_values = OrderMeasurement::where('order_item_id', $item->id)->pluck('measurement_value')->toArray();
               
                // Map measurements with selected values
                $measurements = Measurement::where('product_id', $item->product_id)->orderBy('position','ASC')
                    ->get()
                    ->map(function ($measurement) use ($selected_titles, $selected_values) {
                        $index = array_search($measurement->title, $selected_titles); // Check if title exists in selected titles
                        return [
                            'id' => $measurement->id,
                            'title' => $measurement->title,
                            'short_code' => $measurement->short_code,
                            'value' => $index !== false ? $selected_values[$index] : '', // Assign value if title is in selected titles
                        ];
                });
                return [
                    'product_id' => $item->product_id,
                    'searchproduct' => $item->product_name,
                    'price' => $item->price,
                    'selected_collection' => $item->collection,
                    'collection' => Collection::orderBy('title', 'ASC')->get(),
                    'selected_category' => $item->category,
                    'categories' =>Category::orderBy('title', 'ASC')->where('collection_id', $item->collection)->get(),
                    'sub_category' => $item->sub_category,
                    'selected_fabric' => $item->fabrics,
                    'fabrics' => Fabric::where('product_id', $item->product_id)->get(),
                    'selected_measurements_title' => $selected_titles,
                    'selected_measurements_value' => $selected_values,
                    'measurements' => $measurements,
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

        $this->customer_id = $this->orders->customer_id;
        $this->name = $this->orders->customer_name;
        $this->company_name = $this->orders->customer->company_name;
        $this->employee_rank = $this->orders->customer->employee_rank;
        $this->email = $this->orders->customer_email;
        $this->dob = $this->orders->customer->dob;
        $this->phone = $this->orders->customer->phone;
        $this->whatsapp_no = $this->orders->customer->whatsapp_no;
       

        $this->customers = User::where('user_type', 1)->where('status', 1)->orderBy('name', 'ASC')->get();
        $this->categories = Category::where('status', 1)->orderBy('title', 'ASC')->get();
        $this->collections = Collection::orderBy('title', 'ASC')->get();

        $this->paid_amount = $this->orders->paid_amount;
        $this->billing_amount =  $this->orders->total_amount;
        $this->remaining_amount =  $this->orders->remaining_amount;
        $this->payment_mode = $this->orders->payment_mode;
        // $this->addItem();
    }


    public function addItem()
    {
        $this->items[] = [
           
            'selected_collection' => '',
            'selected_category' => '',
            'collection' => [],
            'categories' => [],
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

    public function rules()
    {
        return [
            // 'items.*.selected_collection' => 'required|exists:collections,id',
            // 'items.*.selected_category' => 'required|exists:categories,id',
            // 'items.*.product_id' => 'required|exists:products,id',
            // 'items.*.price' => 'required|numeric|min:0',
            // 'items.*.measurements.*.value' => 'required|numeric|min:0',
            // 'items.*.selected_fabric' => 'required|exists:fabrics,id',
            'paid_amount' => 'required|numeric|min:1',   // Ensuring that price is a valid number (and greater than or equal to 0).
            'payment_mode' => 'required|string',
        ];
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
        $this->paid_amount = $this->orders->paid_amount;
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
                ->where('collection_id', $this->items[$index]['selected_collection']) // Ensure the selected collection is considered
                ->get();
        }
    }

    public function FindProduct($term, $index)
    {
        $collection = $this->items[$index]['selected_collection'];
        $category = $this->items[$index]['selected_category']; 

        if (empty($collection)) {
            session()->flash('errorProduct.' . $index, '🚨 Please select a collection before searching for a product.');
            return;
        }

        if (empty($category)) {
            session()->flash('errorProduct.' . $index, '🚨 Please select a category before searching for a product.');
            return;
        }
    
        // Clear previous products in the current index
        $this->items[$index]['products'] = [];
    
        if (!empty($term)) {
            // Search for products within the specified collection and matching the term
            $this->items[$index]['products'] = Product::where('collection_id', $collection)
                ->where('category_id', $category)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%')
                          ->orWhere('product_code', 'like', '%' . $term . '%');
                })
                ->get();
        }
    
    }
    // public function updatedSelectedCollection($collectionId)
    // {
    //     // Load categories for the selected collection
    //     $this->categories = Category::where('collection_id', $collectionId)->get();
    //     $this->products = Product::where('category_id', $categoryId)->get();
    //     $this->selectedCategory = null; // Reset category and product selections
    // }

    // public function updatedSelectedCategory($categoryId)
    // {
    //     // Load products for the selected category
    //     $this->products = Product::where('category_id', $categoryId)->get();
    //     $this->selectedProduct = null; // Reset product selection
    // }

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

    // public function checkproductPrice($value, $index)
    // {
    //     // Remove any non-numeric characters except for the decimal point
    //     $formattedValue = preg_replace('/[^0-9.]/', '', $value);

    //     // Check if the value is numeric
    //     if (is_numeric($formattedValue)) {
    //         // Format the value to two decimal places if it's a valid number
    //         // $this->items[$index]['price'] = number_format((float)$formattedValue, 2, '.', '');
    //         session()->forget('errorPrice.' . $index); // Clear any previous error message
    //     } else {
    //         // If the value is invalid, reset the price and show an error message
    //         $this->items[$index]['price'] = 0;
    //         session()->flash('errorPrice.' . $index, '🚨 Please enter a valid price.');
    //     }
    //     $this->updateBillingAmount();  // Update billing amount after checking price
    // }

    public function checkproductPrice($value, $index)
    {
        $selectedFabricId = $this->items[$index]['selected_fabric'] ?? null;

        if ($selectedFabricId) {
            // Fetch fabric data by ID
            $fabricData = Fabric::find($selectedFabricId);

            // Check if fabric exists and validate price
            if ($fabricData) {
                if (floatval($value) < floatval($fabricData->threshold_price)) {
                    // Show error if the price is less than the threshold
                    session()->flash(
                        'errorPrice.' . $index, 
                        "🚨 The price for fabric '{$fabricData->title}' cannot be less than its threshold price of {$fabricData->threshold_price}."
                    );
                    return;
                } else {
                    // Clear error if price is valid
                    session()->forget('errorPrice.' . $index);
                }
            }
        }

        // Sanitize and validate the input
        $formattedValue = preg_replace('/[^0-9.]/', '', $value);

        if (is_numeric($formattedValue)) {
            // Update the value if valid
            $this->items[$index]['price'] = $formattedValue;
        } else {
            // Reset price and show error if invalid
            $this->items[$index]['price'] = 0;
            session()->flash('errorPrice.' . $index, '🚨 Please enter a valid price.');
        }

        $this->updateBillingAmount(); // Update billing after validation
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

    public function update()
    {
        // dd($this->all());
        $this->validate();

        DB::beginTransaction();

        try {

            $total_amount = array_sum(array_column($this->items, 'price'));
            if ($this->paid_amount > $total_amount) {
                session()->flash('error', '🚨 The paid amount cannot exceed the total billing amount.');
                return;
            }
            $this->remaining_amount = $total_amount - $this->paid_amount;

            // Retrieve user details
            $user = User::find($this->customer_id);
            // dd($user);
            if (!$user) {
                // Create new user if not found
                $user = User::create([
                    'name' => $this->name,
                    'company_name' => $this->company_name,
                    'employee_rank' => $this->employee_rank,
                    'email' => $this->email,
                    'dob' => $this->dob,
                    'phone' => $this->phone,
                    'whatsapp_no' => $this->whatsapp_no,
                    'user_type' => 1, // Customer
                ]);
            } else {
                // dd($this->name);
                // Update existing user
                $user->update([
                    'name' => $this->name,
                    'company_name' => $this->company_name,
                    'employee_rank' => $this->employee_rank,
                    'email' => $this->email,
                    'dob' => $this->dob,
                    'phone' => $this->phone,
                    'whatsapp_no' => $this->whatsapp_no,
                    'user_type' => 1, // Customer (if needed, or update as appropriate)
                ]);
                // dd($user);
            }
        // dd($user->address());
            // Update or create addresses
            $billingAddress = $user->address()->updateOrCreate(
                ['address_type' => 1], // Billing address
                [
                    'state' => $this->billing_state,
                    'city' => $this->billing_city,
                    'address' => $this->billing_address,
                    'landmark' => $this->billing_landmark,
                    'country' => $this->billing_country,
                    'zip_code' => $this->billing_pin,
                ]
            );
            // dd($billingAddress);

            if (!$this->is_billing_shipping_same) {
                $shippingAddress = $user->address()->updateOrCreate(
                    ['address_type' => 2], // Shipping address
                    [
                        'state' => $this->shipping_state,
                        'city' => $this->shipping_city,
                        'address' => $this->shipping_address,
                        'landmark' => $this->shipping_landmark,
                        'country' => $this->shipping_country,
                        'zip_code' => $this->shipping_pin,
                    ]
                );
                // dd($shippingAddress);
            }else{
                $shippingAddress = $billingAddress;
            }
            // $order = Order::find($this->orders->id);
            // dd($order);
        // dd( $this->name);
            // Update order details
            $name = $this->name;
            // dd($name);
            $email = $this->email;
            $billingadd = $this->billing_address;
            
            $billingLandmark= $this->billing_landmark;
            $billingCity= $this->billing_city;
            $billingState= $this->billing_state;
            $billingCountry= $this->billing_country;
            $billingPin= $this->billing_pin;

            $shippingadd = $this->shipping_address;
            $shippingLandmark= $this->shipping_landmark;
            $shippingCity= $this->shipping_city;
            $shippingState= $this->shipping_state;
            $shippingCountry= $this->shipping_country;
            $shippingPin= $this->shipping_pin;

            // $total_amount = $total_amount;
            $paid_amount = $this->paid_amount;
            $remaining_amount = $this->remaining_amount;
            $payment_mode = $this->payment_mode;
            $order = Order::find($this->orders->id);
            if (!$order) {
                session()->flash('error', 'Order not found.');
                return redirect()->route('admin.order.index');
            }else{
               
                $order->customer_id = $user->id;
                $order->customer_name = $this->name;
                $order->customer_email = $this->email;
                $order->billing_address = $billingadd . ', ' . $billingLandmark . ', ' . $billingCity . ', ' . $billingState . ', ' . $billingCountry . ' - ' . $billingPin;
                $order->shipping_address = $this->is_billing_shipping_same
                    ? $billingadd . ', ' . $billingLandmark . ', ' . $billingCity . ', ' . $billingState . ', ' . $billingCountry . ' - ' . $billingPin
                    : $shippingadd . ', ' . $shippingLandmark . ', ' . $shippingCity . ', ' . $shippingState . ', ' . $shippingCountry . ' - ' . $shippingPin;
                $order->total_amount = $total_amount;
                $order->paid_amount = $this->paid_amount;
                $order->remaining_amount = $this->remaining_amount;
                $order->payment_mode = $this->payment_mode;
                $order->last_payment_date = now();
                $order->save();
            }
           

            // dd($this->orders);
        // dd($order);
            // Update order items
            // $order = Order::with('items')->find($this->orders->id);
          // Update or create the order items

            foreach ($this->items as $item) {
                // $orderItem = OrderItem::find($item['product_id']);
                $orderItem = OrderItem::where('order_id', $order->id)->where('product_id', $item['product_id'])->first();
                // dd($orderItem->id);
                if ($orderItem) {
                    // dd('test');
                    $orderItem->product_id = $item['product_id'];
                    $orderItem->price = $item['price'];
                    $orderItem->collection = $item['selected_collection'];
                    $orderItem->category = $item['selected_category'];
                    // $orderItem->sub_category = $item['sub_category'];
                    $orderItem->fabrics = $item['selected_fabric'];
                    $orderItem->save();
                    // dd($orderItem);
                        // dd($orderItem);
                    // Update or create measurements for the order item
                    // dd($item['measurements']);
                    // foreach ($item['measurements'] as $measurement) {
                    //     // dd($orderItem->id);
                    //     OrderMeasurement::updateOrCreate(
                    //             ['order_item_id' => $orderItem->id, 'measurement_name' => $measurement['title']],
                    //             ['measurement_value' => $measurement['value']]
                    //         );
                           
                    //     }
                       
                    // }

                    foreach ($item['measurements'] as $measurement) {
                        // Manually check if the OrderMeasurement exists
                        $orderMeasurement = OrderMeasurement::where('order_item_id', $orderItem->id)
                                                            ->where('measurement_name', $measurement['title'])
                                                            ->first();
                        
                        if ($orderMeasurement) {
                            // If the OrderMeasurement exists, update it
                            $orderMeasurement->measurement_value = $measurement['value'];
                            $orderMeasurement->measurement_name = $measurement['title'];
                            $orderMeasurement->save();
                            // dd($orderMeasurement);
                        } else {
                            // If the OrderMeasurement doesn't exist, create a new one
                           $data= OrderMeasurement::create([
                                'order_item_id' => $orderItem->id,
                                'measurement_name' => $measurement['title'],
                                'measurement_value' => $measurement['value'],
                            ]);
                            // dd($data);
                        }
                    }
                    $orderItem = OrderItem::where('order_id', $order->id)->where('product_id', $item['product_id'])->first();

                        // $orderItem->update([
                        //     'selected_fabric' => $item['selected_fabric'], // Save selected fabric ID
                        // ]);
    
                    
                    // dd($data);
                }
            }

            DB::commit();

            session()->flash('success', 'Order has been updated successfully.');
            return redirect()->route('admin.order.index');
        } catch (\Exception $e) {
            DB::rollBack();
            dd( $e->getMessage());
            \Log::error('Error updating order: ' . $e->getMessage());
            session()->flash('error', '🚨 Something went wrong. The operation has been rolled back.');
        }
    }

    // update old
    // public function updateold()
    // {
    //     // dd($this->all());
    //     $this->validate();

    //     DB::beginTransaction();

    //     try {

    //             // 'customer_id' => $user->id,
               



    //         // Retrieve the existing order
    //         $order = Order::findOrFail($this->orders->id);

    //         // Calculate the total amount
    //         $total_amount = array_sum(array_column($this->items, 'price'));
    //         if ($this->paid_amount > $total_amount) {
    //             session()->flash('error', '🚨 The paid amount cannot exceed the total billing amount.');
    //             return;
    //         }
    //         $this->remaining_amount = $total_amount - $this->paid_amount;

    //         // Retrieve user details
    //         $user = User::find($this->customer_id);
    //         // dd($user);
    //         if (!$user) {
    //             // Create new user if not found
    //             $user = User::create([
    //                 'name' => $this->name,
    //                 'company_name' => $this->company_name,
    //                 'employee_rank' => $this->employee_rank,
    //                 'email' => $this->email,
    //                 'dob' => $this->dob,
    //                 'phone' => $this->phone,
    //                 'whatsapp_no' => $this->whatsapp_no,
    //                 'user_type' => 1, // Customer
    //             ]);
    //         } else {
    //             // dd($this->name);
    //             // Update existing user
    //             $user->update([
    //                 'name' => $this->name,
    //                 'company_name' => $this->company_name,
    //                 'employee_rank' => $this->employee_rank,
    //                 'email' => $this->email,
    //                 'dob' => $this->dob,
    //                 'phone' => $this->phone,
    //                 'whatsapp_no' => $this->whatsapp_no,
    //                 'user_type' => 1, // Customer (if needed, or update as appropriate)
    //             ]);
    //             // dd($user);
    //         }
    //     // dd($user->address());
    //         // Update or create addresses
    //         $billingAddress = $user->address()->updateOrCreate(
    //             ['address_type' => 1], // Billing address
    //             [
    //                 'state' => $this->billing_state,
    //                 'city' => $this->billing_city,
    //                 'address' => $this->billing_address,
    //                 'landmark' => $this->billing_landmark,
    //                 'country' => $this->billing_country,
    //                 'zip_code' => $this->billing_pin,
    //             ]
    //         );
    //         // dd($billingAddress);

    //         if (!$this->is_billing_shipping_same) {
    //             $shippingAddress = $user->address()->updateOrCreate(
    //                 ['address_type' => 2], // Shipping address
    //                 [
    //                     'state' => $this->shipping_state,
    //                     'city' => $this->shipping_city,
    //                     'address' => $this->shipping_address,
    //                     'landmark' => $this->shipping_landmark,
    //                     'country' => $this->shipping_country,
    //                     'zip_code' => $this->shipping_pin,
    //                 ]
    //             );
    //             // dd($shippingAddress);
    //         }
    //     // dd( $this->name);
    //         // Update order details
    //         $name = $this->name;
    //         // dd($name);
    //         $email = $this->email;
    //         $billingadd = $this->billing_address;
            
    //         $billingLandmark= $this->billing_landmark;
    //         $billingCity= $this->billing_city;
    //         $billingState= $this->billing_state;
    //         $billingCountry= $this->billing_country;
    //         $billingPin= $this->billing_pin;

    //         $shippingadd = $this->shipping_address;
    //         $shippingLandmark= $this->shipping_landmark;
    //         $shippingCity= $this->shipping_city;
    //         $shippingState= $this->shipping_state;
    //         $shippingCountry= $this->shipping_country;
    //         $shippingPin= $this->shipping_pin;

    //         // $total_amount = $total_amount;
    //         $paid_amount = $this->paid_amount;
    //         $remaining_amount = $this->remaining_amount;
    //         $payment_mode = $this->payment_mode;
    //         $order->update([
    //             'customer_id' => $user->id,
    //             'customer_name' => $name,
    //             'customer_email' => $email,


    //             'billing_address' => $billingAddress->address . ', ' . $this->billing_landmark . ', ' . $this->billing_city . ', ' . $this->billing_state . ', ' . $this->billing_country . ' - ' . $this->billing_pin,
    //             'shipping_address' => $this->is_billing_shipping_same
    //                 ? $order->billing_address
    //                 : $shippingAddress->address . ', ' . $this->shipping_landmark . ', ' . $this->shipping_city . ', ' . $this->shipping_state . ', ' . $this->shipping_country . ' - ' . $this->shipping_pin,
    //             // 'total_amount' => $total_amount,







    //         //     'billing_address' => $billingAddress->address . ', ' . $billingLdmark . ', ' . $billingCity . ', ' . $billingState . ', ' . $billingCountry . ' - ' . $billingPin,
    //         // 'shipping_address' => $this->is_billing_shipping_same
    //         //     ? $order->billing_address
    //         //     : $shippingAddress->address . ', ' . $shippingLandmark . ', ' . $shippingCity . ', ' . $shippingState . ', ' . $shippingCountry . ' - ' . $shippingPin,
    //             'total_amount' => $total_amount,
    //             'paid_amount' => $paid_amount,
    //             'remaining_amount' => $remaining_amount,
    //             'payment_mode' => $payment_mode,
    //             'last_payment_date' => now(),
    //         ]);
    
    //     // dd($order);
    //         // Update order items
    //         $order = Order::with('items')->find($this->orders->id);
    //     // dd($order);
    //         $existingItemIds = $order->items->pluck('id')->toArray();
    //     // dd($existingItemIds);
    //         $newItemIds = array_column($this->items, 'id');

    //         // Delete removed items
    //         // $itemsToDelete = array_diff($existingItemIds, $newItemIds);
    //         // OrderItem::destroy($itemsToDelete);

    //         // foreach ($this->items as $item) {
    //         //     $orderItem = $order->items()->updateOrCreate(
    //         //         ['id' => $item['id'] ?? null],
    //         //         [
    //         //             'product_id' => $item['product_id'],
    //         //             'product_name' => $item['searchproduct'],
    //         //             'price' => $item['price'],
    //         //             'collection' => $item['collection'] ? Collection::find($item['collection'])->id : '',
    //         //             'category' => $item['category'] ? Category::find($item['category'])->id : '',
    //         //             'sub_category' => $item['sub_category'] ? SubCategory::find($item['sub_category'])->title : '',
    //         //             'fabrics' => $item['selected_fabric'] ? Fabric::find($item['selected_fabric'])->title : '',
    //         //         ]
    //         //     );

    //         //     // Update measurements
    //         //     if (isset($item['get_measurements']) && count($item['get_measurements']) > 0) {
    //         //         foreach ($item['get_measurements'] as $mindex => $measurement) {
    //         //             $measurementData = Measurement::find($mindex);
    //         //             $orderItem->measurements()->updateOrCreate(
    //         //                 ['measurement_name' => $measurementData ? $measurementData->title : ''],
    //         //                 ['measurement_value' => $measurement['value']]
    //         //             );
    //         //         }
    //         //     }
    //         // }

    //         DB::commit();

    //         session()->flash('success', 'Order has been updated successfully.');
    //         return redirect()->route('admin.order.index');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Error updating order: ' . $e->getMessage());
    //         session()->flash('error', '🚨 Something went wrong. The operation has been rolled back.');
    //     }
    // }
        /**
         * Helper function to calculate total amount
         */
        private function calculateTotalAmount()
        {
            return array_sum(array_column($this->items, 'price'));
        }

        /**
         * Helper function to calculate remaining amount
         */
        private function calculateRemainingAmount()
        {
            return $this->calculateTotalAmount() - $this->paid_amount;
        }

        /**
         * Helper function to format address
         */
        private function formatAddress($address, $landmark, $city, $state, $country, $pin)
        {
            return "{$address}, {$landmark}, {$city}, {$state}, {$country} - {$pin}";
        }



    public function resetForm()
    {
        // Reset all the form properties
        $this->reset([
            'name',
            'company_name',
            'employee_rank',
            'email',
            'dob',
            'phone',
            'whatsapp_no',
           
        ]);
    }

    public function render()
    {
        // dd($this->order);
        return view('livewire.order.order-edit' ,[
            'order' => $this->orders
        ]);
    }
}
