
<div class="container-fluid px-2 px-md-4">
    <div class="card my-4">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="m-0">Place Order</h4>
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <a href="{{route('admin.order.index')}}" class="btn btn-dark"> <i class="material-icons text-white">chevron_left</i> 
                    Back </a>
            </div>
        </div>
        <div class="card-body" id="sales_order_data">
            <form wire:submit.prevent="save">
                <div class="{{$activeTab==1?"d-block":"d-none"}}" id="tab1">
                    <div class="row d-flex justify-content-between align-items-center mb-3">
                        <!-- Customer Information Badge -->
                        <div class="col-12 col-md-6 mb-2 mb-md-0">
                            <h6 class="badge bg-danger custom_danger_badge">Basic Information</h6>
                        </div>
            
                        <!-- Search Label and Select2 -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex justify-content-between">
                                <!-- Search Label -->
                                <label for="searchCustomer" class="form-label mb-0">Existing Customer</label>
                            </div>
        
                            <div class="position-relative">
                                <input type="text" wire:keyup="FindCustomer($event.target.value)" 
                                    wire:model="searchTerm" 
                                    class="form-control form-control-sm border border-1 customer_input" 
                                    placeholder="Search by customer details">
                                
                                @if(!empty($searchResults))
                                    <div id="fetch_customer_details" class="dropdown-menu show w-100" style="max-height: 200px; overflow-y: auto;">
                                        @foreach ($searchResults as $customer)
                                            <button class="dropdown-item" type="button" wire:click="selectCustomer({{ $customer->id }})">
                                                <img src="{{ $customer->profile_image ? asset($customer->profile_image) : asset('assets/img/user.png') }}" alt=""> {{ $customer->name }} - {{ $customer->phone }} ({{ $customer->email }})
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Customer Details -->
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" id="name" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['name'] ?? '' }}" placeholder="Enter Customer Name">
                            @if(isset($errorMessage['name']))
                                <div class="text-danger">{{ $errorMessage['name'] }}</div>
                            @endif
                        </div>
    
                        <div class="mb-3 col-md-6">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" wire:model="company_name" id="company_name" class="form-control form-control-sm border border-1 p-2" placeholder="Enter Company Name">
                        </div>
    
                        <div class="mb-3 col-md-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" wire:model="email" id="email" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['email'] ?? '' }}" placeholder="Enter Email">
                            @if(isset($errorMessage['email']))
                                <div class="text-danger">{{ $errorMessage['email'] }}</div>
                            @endif
                        </div>
    
                        <div class="mb-3 col-md-3">
                            <label for="dob" class="form-label">Date Of Birth <span class="text-danger">*</span></label>
                            <input type="date" wire:model="dob" id="dob" max="{{date('Y-m-d')}}" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['dob'] ?? '' }}">
                            @if(isset($errorMessage['dob']))
                                <div class="text-danger">{{ $errorMessage['dob'] }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="number" wire:model="phone" id="phone" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['phone'] ?? '' }}" placeholder="Enter Phone Number">
                            @if(isset($errorMessage['phone']))
                                <div class="text-danger">{{ $errorMessage['phone'] }}</div>
                            @endif
                        </div>
    
                        <div class="mb-3 col-md-3">
                            <label for="whatsapp_no" class="form-label">WhatsApp Number <span class="text-danger">*</span></label>
                            <input type="number" wire:model="whatsapp_no" id="whatsapp_no" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['whatsapp_no'] ?? '' }}" placeholder="Enter Whatsapp Number"  @if($whatsapp_no)disabled @endif>
                            @if(isset($errorMessage['whatsapp_no']))
                                <div class="text-danger">{{ $errorMessage['whatsapp_no'] }}</div>
                            @endif
                            <input type="checkbox" id="is_wa_same" wire:change="SameAsMobile" value="0" @if($is_wa_same) checked @endif>
                            <label for="is_wa_same" class="form-check-label ms-2">Same as Phone Number</label>
                        </div>
                    </div>
                    
                    {{-- Billing Address --}}
                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                        <h6 class="badge bg-danger custom_danger_badge">Billing Address</h6>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="billing_address" class="form-label">Street Address <span class="text-danger">*</span></label>
                            <textarea wire:model="billing_address" id="billing_address" cols="30" rows="3" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['billing_address'] ?? '' }}" placeholder="Enter billing address" ></textarea>
                            @if(isset($errorMessage['billing_address']))
                                <div class="text-danger">{{ $errorMessage['billing_address'] }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="billing_landmark" class="form-label">Landmark</label>
                            <input type="text" wire:model="billing_landmark" id="billing_landmark" class="form-control form-control-sm border border-1 p-2" placeholder="Enter landmark">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="billing_city" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" wire:model="billing_city" id="billing_city" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['billing_city'] ?? '' }}" placeholder="Enter city">
                            @if(isset($errorMessage['billing_city']))
                                <div class="text-danger">{{ $errorMessage['billing_city'] }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="billing_state" class="form-label">State <span class="text-danger">*</span></label>
                            <input type="text" wire:model="billing_state" id="billing_state" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['billing_state'] ?? '' }}" placeholder="Enter state">
                            @if(isset($errorMessage['billing_state']))
                                <div class="text-danger">{{ $errorMessage['billing_state'] }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="billing_country" class="form-label">Country <span class="text-danger">*</span></label>
                            <input type="text" wire:model="billing_country" id="billing_country" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['billing_country'] ?? '' }}" placeholder="Enter country">
                            @if(isset($errorMessage['billing_country']))
                                <div class="text-danger">{{ $errorMessage['billing_country'] }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="billing_pin" class="form-label">Zip Code <span class="text-danger">*</span></label>
                            <input type="number" wire:model="billing_pin" id="billing_pin" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['billing_pin'] ?? '' }}" placeholder="Enter PIN">
                            @if(isset($errorMessage['billing_pin']))
                                <div class="text-danger">{{ $errorMessage['billing_pin'] }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <h6 class="badge bg-danger custom_danger_badge">Shipping Address</h6>
                        <div class="form-check">
                            <input type="checkbox"  wire:change="toggleShippingAddress" wire:model="is_billing_shipping_same" id="isBillingShippingSame" class="form-check-input" @if ($is_billing_shipping_same) checked @endif>
                            <label for="isBillingShippingSame" class="form-check-label"><span class="badge bg-secondary">Shipping address same as billing</span></label>
                        </div>
                    </div>
                    
                    {{-- Shipping Address Panel --}}
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="shipping_address" class="form-label">Street Address <span class="text-danger">*</span></label>
                            <textarea  wire:model="shipping_address" id="shipping_address" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['shipping_address'] ?? '' }}" placeholder="Enter shipping address"></textarea>
                            @if(isset($errorMessage['shipping_address']))
                                <div class="text-danger">{{ $errorMessage['shipping_address'] }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="shipping_landmark" class="form-label">Landmark</label>
                            <input type="text" wire:model="shipping_landmark" id="shipping_landmark" class="form-control form-control-sm border border-1 p-2" placeholder="Enter landmark">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="shipping_city" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" wire:model="shipping_city" id="shipping_city" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['shipping_city'] ?? '' }}" placeholder="Enter city">
                            @if(isset($errorMessage['shipping_city']))
                                <div class="text-danger">{{ $errorMessage['shipping_city'] }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="shipping_state" class="form-label">State <span class="text-danger">*</span></label>
                            <input type="text" wire:model="shipping_state" id="shipping_state" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['shipping_state'] ?? '' }}" placeholder="Enter state">
                            @if(isset($errorMessage['shipping_state']))
                                <div class="text-danger">{{ $errorMessage['shipping_state'] }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="shipping_country" class="form-label">Country <span class="text-danger">*</span></label>
                            <input type="text" wire:model="shipping_country" id="shipping_country" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['shipping_country'] ?? '' }}" placeholder="Enter country">
                            @if(isset($errorMessage['shipping_country']))
                                <div class="text-danger">{{ $errorMessage['shipping_country'] }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="shipping_pin" class="form-label">Zip Code <span class="text-danger">*</span></label>
                            <input type="number" wire:model="shipping_pin" id="shipping_pin" class="form-control form-control-sm border border-1 p-2 {{ $errorClass['shipping_pin'] ?? '' }}" placeholder="Enter PIN">
                            @if(isset($errorMessage['shipping_pin']))
                                <div class="text-danger">{{ $errorMessage['shipping_pin'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="{{$activeTab==2?"d-block":"d-none"}}" id="tab2">
                   <div class="row">
                        <div class="col-12 col-md-12 mb-2 mb-md-0">
                            <h6 class="badge bg-danger custom_danger_badge">Product Information</h6>
                        </div>
                   </div>
                    <div class="row">
                        <!-- Category -->
                        <div class="mb-3 col-md-3">
                            <label for="category" class="form-label">Category</label>
                            <select id="category" class="form-select form-control-sm border border-1" wire:model="category" wire:change="CatWiseSubCatProduct($event.target.value)">
                                <option value="" selected hidden> Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <!-- Sub-Category -->
                        <div class="mb-3 col-md-3">
                            <label for="subcategory" class="form-label">Sub-Category</label>
                            <select id="subcategory" class="form-select form-control-sm border border-1" wire:model="selectedSubCategory" wire:change="SubCatWiseProduct($event.target.value)">
                                <option value="" selected hidden>Select Sub-Category</option>
                                @foreach($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}">{{ $subCategory->title }}</option>
                                @endforeach
                            </select>
                            @error('selectedSubCategory')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <!-- Product -->
                        <div class="mb-3 col-md-6">
                            <label for="product" class="form-label">Product</label>
                            <input type="text" 
                                wire:keyup="FindPrduct($event.target.value)" 
                                wire:model="searchproduct" 
                                class="form-control form-control-sm border border-1 customer_input" 
                                placeholder="Enter product name"
                                wire:click="GetProduct({{ $selectedCategory ?? 'null' }}, {{ $selectedSubCategory ?? 'null' }})">

                                    <input type="hidden" wire:model="product_id" value="{{$product_id}}">
                                @if($FetchProduct !=1 && count($products)>0)
                                    <div id="fetch_customer_details" class="dropdown-menu show w-50" style="max-height: 200px; overflow-y: auto;">
                                        @foreach ($products as $product)
                                            <button class="dropdown-item" type="button" wire:click='selectProduct("{{ $product->name }}", {{$product->id}})'>
                                                <img src="{{ $product->product_image ? asset($product->product_image) : asset('assets/img/cubes.png') }}" alt=""> {{ $product->name }}({{ $product->product_code }})
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                        </div>
                    </div>
                    @if(isset($product_id))
                        <div class="row">
                            <div class="col-12 col-md-12 mb-2 mb-md-0">
                                <h6 class="badge bg-danger custom_danger_badge">Measurements</h6>
                            </div>
                            <div class="col-12 col-md-6 mb-2 mb-md-0 measurement_div">
                                <div class="row">
                                    @forelse ($measurements as $index => $item)
                                        <div class="col-md-3">
                                            <label>{{ $item->title }} <strong>[{{$item->short_code}}]</strong> </label>
                                            <input type="text" class="form-control form-control-sm border border-1 customer_input text-center measurement_input">
                                        </div>
                                    @empty
                                        @if (session('measurements_error'))
                                            <div class="alert alert-danger">
                                                {{ session('measurements_error') }}
                                            </div>
                                        @endif
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="d-flex justify-content-end align-items-center mb-3">
                    @if($activeTab>1)
                    <button type="button" class="btn btn-dark mx-2" wire:click="TabChange({{$activeTab-1}})"><i class="material-icons text-white">chevron_left</i>Previous</button>
                    @endif
                    <button type="button" class="btn btn-primary mx-2" wire:click="TabChange({{$activeTab+1}})">Next<i class="material-icons text-white">chevron_right</i></button>
                </div>
            </form>
            <!-- Tabs content -->
               
        </div>
    </div>
</div>
<script>
    // document.addEventListener('DOMContentLoaded', function () {
    //     // Set the first tab and content as active by default
    //     const firstTab = document.querySelector('#sales_order_data .nav-link');
    //     const firstPane = document.querySelector('#sales_order_data .tab-pane');

    //     if (firstTab && firstPane) {
    //         firstTab.classList.add('active');
    //         firstPane.classList.add('show', 'active');
    //     }

    //     // Attach event listeners to tabs under #sales_order_data
    //     document.querySelectorAll('#sales_order_data .nav-link').forEach(function (tabLink) {
    //         tabLink.addEventListener('click', function (event) {
    //             event.preventDefault();

    //             // Remove 'active' class from all tabs and content
    //             document.querySelectorAll('#sales_order_data .nav-link').forEach(function (link) {
    //                 link.classList.remove('active');
    //             });
    //             document.querySelectorAll('#sales_order_data .tab-pane').forEach(function (pane) {
    //                 pane.classList.remove('show', 'active');
    //             });

    //             // Add 'active' class to clicked tab and corresponding content
    //             const targetId = this.getAttribute('href').substring(1);
    //             this.classList.add('active');
    //             const targetPane = document.getElementById(targetId);
    //             if (targetPane) {
    //                 targetPane.classList.add('show', 'active');
    //             }
    //         });
    //     });
    // });
</script>

