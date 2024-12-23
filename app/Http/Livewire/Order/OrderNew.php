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
    public $collectionsType = [];
    public $Collections = [];
    public $errorMessage = [];
    public $activeTab = 1;
    public $items = [];
    public $FetchProduct = 1;

    public $customers = null;
    public $is_wa_same, $name, $company_name,$employee_rank, $email, $dob, $customer_id, $whatsapp_no, $phone;
    public $billing_address,$billing_landmark,$billing_city,$billing_state,$billing_country,$billing_pin;

    public $is_billing_shipping_same;

    public $shipping_address,$shipping_landmark,$shipping_city,$shipping_state,$shipping_country,$shipping_pin;

    //  product 
    public $categories,$subCategories = [], $products = [], $measurements = [];
    public $selectedCategory = null, $selectedSubCategory = null,$searchproduct, $product_id =null,$collection_type,$collection;
    public $billingAmount = 0;

    public function mount(){
        $this->customers = User::where('user_type', 1)->where('status', 1)->orderBy('name', 'ASC')->get();
        $this->collectionsType = CollectionType::orderBy('title', 'ASC')->get();
        $this->categories = Category::where('status', 1)->orderBy('title', 'ASC')->get();
        $this->addItem();
    }

    // Define rules for validation
    protected $rules = [
        'items.*.collection_type' => 'required|string',
        'items.*.collection' => 'required|string',
        'items.*.product_id' => 'required|integer',
        'items.*.price' => 'required|numeric|min:0',  // Ensuring that price is a valid number (and greater than or equal to 0).
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
        } else {
            $this->searchResults = [];
        }
    }


    

    public function addItem()
    {
        $this->items[] = [
            'collection_type' => '',
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

    public function GetCollection($typeId, $index)
    {
        // Reset collection, products, and product_id for the selected item
        $this->items[$index]['collection'] = ''; 
        $this->items[$index]['products'] = [];
        $this->items[$index]['product_id'] = null;
        $this->items[$index]['measurements'] = [];
        $this->items[$index]['fabrics'] = [];
        $this->items[$index]['categories'] = [];
        if ($typeId) {
            // Fetch collections based on the selected collection type
            $this->items[$index]['collections'] = Collection::where('collection_type', $typeId)
                ->orderBy('title', 'ASC')
                ->get();
        }
    }
    
    public function CollectionWiseProduct($value, $index)
    {
        
        // If a collection is selected, fetch the products and categories
        if ($value) {
            // Fetch products related to the selected collection
            $this->items[$index]['products'] = Product::where('collection_id', $value)->get();
            
            // Fetch categories based on products related to the selected collection
            $bulkCategory = Product::where('collection_id', $value)
                ->pluck('category_id')
                ->toArray();

            // Fetch and set categories
            $this->items[$index]['categories'] = Category::where('status', 1)
                ->whereIn('id', $bulkCategory)
                ->orderBy('title', 'ASC')
                ->get();
        } else {
            // Reset products and categories if no collection is selected
            $this->items[$index]['products'] = [];
            $this->items[$index]['categories'] = [];
        }
    }

    public function CatWiseSubCatProduct($categoryId, $index)
    {
        // Reset products and product_id for the selected item
        $this->items[$index]['products'] = [];
        $this->items[$index]['product_id'] = null;

        if ($categoryId) {
            // Fetch subcategories and products based on the selected category
            $this->subCategories = SubCategory::where('category_id', $categoryId)->get();
            $this->items[$index]['products'] = Product::where('category_id', $categoryId)->get();
        } else {
            // Reset subcategories and products if no category is selected
            $this->subCategories = [];
            $this->items[$index]['products'] = [];
        }
    }


    public function FindProduct($term, $index)
    {
        $collection = $this->items[$index]['collection'];
    
        if (empty($collection)) {
            session()->flash('errorProduct.' . $index, '🚨 Please select a collection before searching for a product.');
            return;
        }
    
        // Clear previous products in the current index
        $this->items[$index]['products'] = [];
    
        if (!empty($term)) {
            // Search for products within the specified collection and matching the term
            $this->items[$index]['products'] = Product::where('collection_id', $collection)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%')
                          ->orWhere('product_code', 'like', '%' . $term . '%');
                })
                ->get();
        }
    
        // Optional: Log the products for debugging
        // dd($this->items[$index]['products']);
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
        $this->billingAmount = array_sum(array_column($this->items, 'price'));
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

    public function save()
    {
        // dd($this->items);
        // Validate the input fields based on the rules
        $this->validate();
    
        DB::beginTransaction();  // Begin transaction
    
        try {
            // Create the order (this will hold general info about the order)
            $order = new Order();
            $order->order_number = 'ORD-' . strtoupper(uniqid()); // Generate a unique order number
            $order->customer_name = $this->name;
            $order->customer_email = $this->email;
    
            // Construct the billing address (assuming you have the billing data available)
            $billing_address = $this->billing_address . ', ' . $this->billing_landmark . ', ' . $this->billing_city . ', ' . $this->billing_state . ', ' . $this->billing_country . ' - ' . $this->billing_pin;
            $order->billing_address = $billing_address;
    
            // Check if shipping address is same as billing address
            if ($this->is_billing_shipping_same) {
                $order->shipping_address = $billing_address;
            } else {
                $shipping_address = $this->shipping_address . ', ' . $this->shipping_landmark . ', ' . $this->shipping_city . ', ' . $this->shipping_state . ', ' . $this->shipping_country . ' - ' . $this->shipping_pin;
                $order->shipping_address = $shipping_address;
            }
    
            // Calculate the total amount (you can calculate this based on item prices)
            $total_amount = array_sum(array_column($this->items, 'price'));  // Assuming $this->items contains the price of each item
            $order->total_amount = $total_amount;
    
            $order->save();  // Save the order
    
            // Now loop through the items and save them as OrderItem and OrderMeasurement
            foreach ($this->items as $k=>$item) {
                // Create and save the order item
                $collection_data = Collection::where('id',$item['collection'])->first();
                $category_data = Category::where('id',$item['category'])->first();
                $sub_category_data = SubCategory::where('id',$item['sub_category'])->first();

                $fabric_data = Fabric::where('id',$item['selected_fabric'])->first();
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->collection_type = $item['collection_type'];
                $orderItem->collection = $collection_data?$collection_data->title:"";
                $orderItem->category = $category_data?$category_data->title:"";
                $orderItem->sub_category = $sub_category_data?$sub_category_data->title:"";
                $orderItem->product_name = $item['searchproduct'];
                $orderItem->price = $item['price'];
                $orderItem->fabrics = $fabric_data?$fabric_data->title:"";  // Save fabric selection
                $orderItem->save();  // Save the order item
    
                // Now handle the measurements for each item
                if ($item['collection_type']==1 && isset($item['get_measurements']) && count($item['get_measurements']) > 0) {
                    foreach ($item['get_measurements'] as $mindex =>$measurement) {
                        $measurement_data = Measurement::where('id', $mindex)->first();
                        // Save the measurement for this order item
                        $orderMeasurement = new OrderMeasurement();
                        $orderMeasurement->order_item_id = $orderItem->id;
                        $orderMeasurement->measurement_name = $measurement_data?$measurement_data->title:"";  // Assuming 'title' is the name of the measurement
                        $orderMeasurement->measurement_value = $measurement['value'];  // Assuming 'value' is the entered value
                        $orderMeasurement->save();  // Save the order measurement
                    }
                }
            }

            // Commit the transaction if everything is fine
            DB::commit();
    
            // Flash success message
            session()->flash('success', 'Order has been generated successfully.');
            return redirect()->route('admin.order.index');
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();
            // dd($e->getMessage());
            // Flash error message
            session()->flash('error', '🚨 Something went wrong. The operation has been rolled back.');
    
            // Optionally log the error
            \Log::error('Error saving items: ' . $e->getMessage());
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


    public function render()
    {
        return view('livewire.order.order-new', [
            'collectionsType' => $this->collectionsType,
            'categories' => $this->categories,
        ]);
    }
    
}
