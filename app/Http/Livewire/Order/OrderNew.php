<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Collection;
use App\Models\Fabric;
use App\Models\CollectionType;
use App\Models\Measurement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderMeasurement;
use Illuminate\Support\Facades\DB;

class OrderNew extends Component
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

    public function mount(){
        $this->customers = User::where('user_type', 1)->where('status', 1)->orderBy('name', 'ASC')->get();
        $this->categories = Category::where('status', 1)->orderBy('title', 'ASC')->get();
        $this->collections = Collection::orderBy('title', 'ASC')->get();
        $this->addItem();
    }

    // Define rules for validation
    protected $rules = [
        'items.*.collection' => 'required|string',
        'items.*.product_id' => 'required|integer',
        'items.*.price' => 'required|numeric|min:1',  // Ensuring that price is a valid number (and greater than or equal to 0).
        'paid_amount' => 'required|numeric|min:1',   // Ensuring that price is a valid number (and greater than or equal to 0).
        'payment_mode' => 'required|string',  // Ensuring that price is a valid number (and greater than or equal to 0).
        'items.*.measurements.*' => 'nullable|string',
    ];
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
                $orders = Order::where('order_number', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('customer', function ($query) {
                        $query->where('name', 'like', '%' . $this->searchTerm . '%');
                    })
                    ->latest()
                    ->take(1)
                    ->get();

                if ($orders->count()) {
                    // If orders are found, show the customer name, phone, and email in search results
                    $this->orders = $orders;
        // dd($orders);
                    // Prepend customer details from the first order into search results
                    $customerFromOrder = $orders->first()->customer;
                    // dd($customerFromOrder);
                    $this->searchResults->prepend($customerFromOrder);
                    session()->flash('orders-found', 'Orders found for this customer.');
                } else {
                    $this->orders = collect(); // No orders found
                    session()->flash('no-orders-found', 'No orders found for this customer.');
                }

                 // If no orders are found, flash a session message
            // if ($this->orders->isEmpty()) {
            // }
        } else {
            // Reset results when the search term is empty
            $this->searchResults = [];
            $this->orders = collect(); 
        }
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

    // public function addMeasurement($index, $measurement)
    // {
    //     // Initialize measurements array if it's not already set for the specific item
    //     if (!isset($this->items[$index]['measurements'])) {
    //         $this->items[$index]['measurements'] = [];
    //     }
    
    //     // Add the measurement to the measurements array
    //     $this->items[$index]['measurements'][$measurement->id] = [
    //         'title' => $measurement->title,
    //         'short_code' => $measurement->short_code,
    //         'value' => '',  // Initialize the value as empty or with a default value
    //     ];
    // }

    

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->updateBillingAmount();  // Update billing amount after checking price
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



    public function FindProduct($term, $index)
    {
        $collection = $this->items[$index]['collection'];
        $category = $this->items[$index]['category']; 

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

    

    // public function selectProduct($index, $name, $id)
    // {
    //     $this->items[$index]['searchproduct'] = $name;
    //     $this->items[$index]['product_id'] = $id;
    //     $this->items[$index]['products'] = [];
    //     $this->items[$index]['measurements'] = Measurement::where('product_id', $id)->where('status', 1)->orderBy('position','ASC')->get();
    //     $this->items[$index]['fabrics'] = Fabric::where('product_id', $id)->where('status', 1)->get();
        
    //     session()->forget('measurements_error.' . $index);
    //     if (count($this->items[$index]['measurements']) == 0) {
    //         session()->flash('measurements_error.' . $index, '🚨 Oops! Measurement data not added for this product.');
    //         return;
    //     }
    // }

    public function selectProduct($index, $name, $id)
    {
        // Set the selected product details
        $this->items[$index]['searchproduct'] = $name;
        $this->items[$index]['product_id'] = $id;
        $this->items[$index]['products'] = [];
        
        // Get the measurements available for the selected product
        $this->items[$index]['measurements'] = Measurement::where('product_id', $id)
                                                            ->where('status', 1)
                                                            ->orderBy('position','ASC')
                                                            ->get();
        // Get the fabrics available for the selected product
        $this->items[$index]['fabrics'] = Fabric::where('product_id', $id)
                                                  ->where('status', 1)
                                                  ->get();
        
        // Clear any previous measurement error session
        session()->forget('measurements_error.' . $index);
        
        // Check if there are no measurements for the product
        if (count($this->items[$index]['measurements']) == 0) {
            session()->flash('measurements_error.' . $index, '🚨 Oops! Measurement data not added for this product.');
            return;
        }
    
        // Auto-populate measurements if the user has ordered this product before
        $this->populatePreviousOrderMeasurements($index, $id);
        // dd( $this->populatePreviousOrderMeasurements($index, $id));
    }
    public function populatePreviousOrderMeasurements($index, $productId)
    {
        // Find previous order for this customer that includes the selected product
        $previousOrder = OrderItem::where('product_id', $productId)
                                  ->whereHas('order', function($query) {
                                      $query->where('customer_id', $this->customer_id); // Ensure the same customer
                                  })
                                  ->latest()
                                  ->first(); // Get the most recent order for the product
        if ($previousOrder) {
            // Get the measurements related to this previous order's product
            $previousMeasurements = OrderMeasurement::where('order_item_id', $previousOrder->id)->get();
            foreach ($previousMeasurements as $previousMeasurement) {
                // $this->items[$index]['get_measurements'][$previousMeasurement->measurement_name]['value'] = $previousMeasurement->measurement_value;
    
                $measurement = $previousMeasurement->measurement; // This will return the related Measurement model
                $this->items[$index]['get_measurements'][$measurement->id]['value'] = $previousMeasurement->measurement_value;
            }
        }
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction(); // Begin transaction

        try {
            // Calculate the total amount
            $total_amount = array_sum(array_column($this->items, 'price'));
            if ($this->paid_amount > $total_amount) {
                session()->flash('error', '🚨 The paid amount cannot exceed the total billing amount.');
                return;
            }
            $this->remaining_amount = $total_amount - $this->paid_amount;

            // Retrieve user details
            $user = User::find($this->customer_id);
             // If customer does not exist, create a new user
            if (!$user) {
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
             } 
                // Store Billing Address for the new user
             $billingAddress = $user->address()->where('address_type', 1)->first();
             if (!$billingAddress) {
                 $user->address()->create([
                     'address_type' => 1, // Billing address
                     'state' => $this->billing_state,
                     'city' => $this->billing_city,
                     'address' => $this->billing_address,
                     'landmark' => $this->billing_landmark,
                     'country' => $this->billing_country,
                     'zip_code' => $this->billing_pin,
                 ]);
             }
                // Store Shipping Address if applicable
                if (!$this->is_billing_shipping_same) {
                    $shippingAddress = $user->address()->where('address_type', 2)->first();
                    if (!$shippingAddress) {
                        $user->address()->create([
                            'address_type' => 2, // Shipping address
                            'state' => $this->shipping_state,
                            'city' => $this->shipping_city,
                            'address' => $this->shipping_address,
                            'landmark' => $this->shipping_landmark,
                            'country' => $this->shipping_country,
                            'zip_code' => $this->shipping_pin,
                        ]);
                    }
                }


            if ($user) {
                // Retrieve existing billing address
                $existingBillingAddress = $user->address()->where('address_type', 1)->first();
                // dd($existingBillingAddress);
                // Check and update billing address if needed
                $billingAddressUpdated = false;
                if ($existingBillingAddress) {
                    if (
                        $existingBillingAddress->state !== $this->billing_state ||
                        $existingBillingAddress->city !== $this->billing_city ||
                        $existingBillingAddress->address !== $this->billing_address
                    ) {
                        $existingBillingAddress->update([
                            'state' => $this->billing_state,
                            'city' => $this->billing_city,
                            'address' => $this->billing_address,
                            'landmark' => $this->billing_landmark,
                            'country' => $this->billing_country,
                            'zip_code' => $this->billing_pin,
                        ]);
                        $billingAddressUpdated = true;
                    }
                } else {
                    // Create new billing address if none exists
                    $user->address()->create([
                        'address_type' => 1, // Billing address
                        'state' => $this->billing_state,
                        'city' => $this->billing_city,
                        'address' => $this->billing_address,
                        'landmark' => $this->billing_landmark,
                        'country' => $this->billing_country,
                        'zip_code' => $this->billing_pin,
                    ]);
                    $billingAddressUpdated = true;
                }

                // Perform similar logic for shipping address
                $existingShippingAddress = $user->address()->where('address_type', 2)->first();
                if ($this->is_billing_shipping_same) {
                    if ($existingShippingAddress) {
                        $existingShippingAddress->update([
                            'state' => $this->billing_state,
                            'city' => $this->billing_city,
                            'address' => $this->billing_address,
                            'landmark' => $this->billing_landmark,
                            'country' => $this->billing_country,
                            'zip_code' => $this->billing_pin,
                        ]);
                    } else {
                        $user->address()->create([
                            'address_type' => 2, // Shipping address
                            'state' => $this->billing_state,
                            'city' => $this->billing_city,
                            'address' => $this->billing_address,
                            'landmark' => $this->billing_landmark,
                            'country' => $this->billing_country,
                            'zip_code' => $this->billing_pin,
                        ]);
                    }
                } else {
                    if ($existingShippingAddress) {
                        if (
                            $existingShippingAddress->state !== $this->shipping_state ||
                            $existingShippingAddress->city !== $this->shipping_city ||
                            $existingShippingAddress->address !== $this->shipping_address
                        ) {
                            $existingShippingAddress->update([
                                'state' => $this->shipping_state,
                                'city' => $this->shipping_city,
                                'address' => $this->shipping_address,
                                'landmark' => $this->shipping_landmark,
                                'country' => $this->shipping_country,
                                'zip_code' => $this->shipping_pin,
                            ]);
                        }
                    } else {
                        $user->address()->create([
                            'address_type' => 2, // Shipping address
                            'state' => $this->shipping_state,
                            'city' => $this->shipping_city,
                            'address' => $this->shipping_address,
                            'landmark' => $this->shipping_landmark,
                            'country' => $this->shipping_country,
                            'zip_code' => $this->shipping_pin,
                        ]);
                    }
                }
            }

            // Create the order
            $order = new Order();
            $order->order_number = 'ORD-' . strtoupper(uniqid());
            $order->customer_id = $user->id;
            $order->customer_name = $this->name;
            $order->customer_email = $this->email;
            $order->billing_address = $this->billing_address . ', ' . $this->billing_landmark . ', ' . $this->billing_city . ', ' . $this->billing_state . ', ' . $this->billing_country . ' - ' . $this->billing_pin;

            if ($this->is_billing_shipping_same) {
                $order->shipping_address = $order->billing_address;
            } else {
                $order->shipping_address = $this->shipping_address . ', ' . $this->shipping_landmark . ', ' . $this->shipping_city . ', ' . $this->shipping_state . ', ' . $this->shipping_country . ' - ' . $this->shipping_pin;
            }

            $order->total_amount = $total_amount;
            $order->paid_amount = $this->paid_amount;
            $order->remaining_amount = $this->remaining_amount;
            $order->payment_mode = $this->payment_mode;
            $order->last_payment_date = date('Y-m-d H:i:s');
            $order->save();

               // Validate fabric prices before generating the order
               foreach ($this->items as $item) {
                   $fabric_data = Fabric::find($item['selected_fabric']);
                   if ($fabric_data && isset($item['price']) && $item['price'] < $fabric_data->threshold_price) {
                    session()->flash('error', '🚨 The price for fabric "' . $fabric_data->title . '" cannot be less than its threshold price of ' . $fabric_data->threshold_price . '.');
                    return;
                }
               }

            // Save order items and measurements
            foreach ($this->items as $k => $item) {
                $collection_data = Collection::find($item['collection']);
                $category_data = Category::find($item['category']);
                $sub_category_data = SubCategory::find($item['sub_category']);
                $fabric_data = Fabric::find($item['selected_fabric']);

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->collection = $collection_data ? $collection_data->title : "";
                $orderItem->category = $category_data ? $category_data->title : "";
                $orderItem->sub_category = $sub_category_data ? $sub_category_data->title : "";
                $orderItem->product_name = $item['searchproduct'];
                $orderItem->price = $item['price'];
                $orderItem->fabrics = $fabric_data ? $fabric_data->title : "";
                $orderItem->save();

                if (isset($item['get_measurements']) && count($item['get_measurements']) > 0) {
                    foreach ($item['get_measurements'] as $mindex => $measurement) {
                        $measurement_data = Measurement::find($mindex);

                        $orderMeasurement = new OrderMeasurement();
                        $orderMeasurement->order_item_id = $orderItem->id;
                        $orderMeasurement->measurement_name = $measurement_data ? $measurement_data->title : "";
                        $orderMeasurement->measurement_value = $measurement['value'];
                        $orderMeasurement->save();
                    }
                }
            }

            DB::commit();

            session()->flash('success', 'Order has been generated successfully.');
            return redirect()->route('admin.order.index');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saving order: ' . $e->getMessage());
            session()->flash('error', '🚨 Something went wrong. The operation has been rolled back.');
        }
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

    public function selectCustomer($customerId)
    {
        $this->resetForm(); // Reset form to default values

        $customer = User::find($customerId);

        if ($customer) {
            // Populate customer details
            $this->customer_id = $customer->id;
            $this->name = $customer->name;
            $this->company_name = $customer->company_name;
            $this->employee_rank = $customer->employee_rank;
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
    private function populateAddress($type, $address)
    {
        // dd($address);
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


    public function render()
    {
        return view('livewire.order.order-new', [
            // 'collectionsType' => $this->collectionsType,
            'categories' => $this->categories,
        ]);
    }
    
}
