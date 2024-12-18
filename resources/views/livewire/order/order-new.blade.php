
<div class="container-fluid px-2 px-md-4">
    <div class="d-flex justify-content-between mb-3">
        <h4 class="m-0">Place Order</h4>
    </div>
    <div class="card my-4">
        <div class="card-header pb-0">
            <div class="row d-flex justify-content-between align-items-center mb-3">
                <!-- Customer Information Badge -->
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <h6 class="badge bg-danger custom_danger_badge">Customer Information</h6>
                </div>
    
                <!-- Search Label and Select2 -->
                <div class="col-12 col-md-6">
                    <div class="d-flex justify-content-between">
                        <!-- Search Label -->
                        <label for="searchCustomer" class="form-label mb-0">Search Existing Customer</label>
                    </div>
                    <!-- Select2 Dropdown for searching -->
                    <select id="searchCustomer" class="form-select form-control-sm border border-2" wire:change="selectCustomer($event.target.value)" style="padding-left: 20px;">
                        <option value="AK" data-image="{{asset('assets/img/user.png') }}" selected>Select customer</option>
                        @foreach ($customers as $item)
                            <option value="{{ $item->id }}" data-image="{{ $item->profile_image ? asset('storage/'.$item->profile_image) : asset('assets/img/user.png') }}">
                                {{$item->name}} - ({{$item->phone}})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row">
                    <!-- Customer Details -->
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" wire:model="name" id="name" class="form-control form-control-sm border border-2 p-2" placeholder="Enter Customer Name" @if($name) disabled @endif>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" wire:model="company_name" id="company_name" class="form-control form-control-sm border border-2 p-2" placeholder="Enter Company Name" @if($company_name) disabled @endif>
                        @error('company_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" wire:model="email" id="email" class="form-control form-control-sm border border-2 p-2" placeholder="Enter Email" @if($email) disabled @endif>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-3">
                        <label for="dob" class="form-label">Date Of Birth <span class="text-danger">*</span></label>
                        <input type="date" wire:model="dob" id="dob" class="form-control form-control-sm border border-2 p-2"  @if($dob) disabled @endif>
                        
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-3">
                        <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" wire:model="phone" id="phone" class="form-control form-control-sm border border-2 p-2" placeholder="Enter Phone Number"  @if($phone) disabled @endif>
                        
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-3">
                        <label for="whatsapp_no" class="form-label">WhatsApp Number <span class="text-danger">*</span></label>
                        <input type="text" wire:model="whatsapp_no" id="whatsapp_no" class="form-control form-control-sm border border-2 p-2" @if($is_wa_same) disabled @endif placeholder="Enter Whatsapp Number"  @if($whatsapp_no)disabled @endif>

                        <input type="checkbox" id="is_wa_same" wire:change="SameAsMobile" value="0" @if($is_wa_same) checked @endif>
                        <label for="is_wa_same" class="form-check-label ms-2">Same as Phone Number</label>
                        @error('whatsapp_no')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <h6 class="badge bg-danger custom_danger_badge">Customer Billing Address</h6>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="billing_address" class="form-label">Street Address</label>
                        <textarea wire:model="billing_address" id="billing_address" cols="30" rows="3" class="form-control form-control-sm border border-2 p-2" placeholder="Enter billing address" ></textarea>
                        @error('billing_address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="billing_landmark" class="form-label">Landmark</label>
                        <input type="text" wire:model="billing_landmark" id="billing_landmark" class="form-control form-control-sm border border-2 p-2" placeholder="Enter landmark">
                        @error('billing_landmark')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="billing_city" class="form-label">City</label>
                        <input type="text" wire:model="billing_city" id="billing_city" class="form-control form-control-sm border border-2 p-2" placeholder="Enter city">
                        @error('billing_city')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="billing_state" class="form-label">State</label>
                        <input type="text" wire:model="billing_state" id="billing_state" class="form-control form-control-sm border border-2 p-2" placeholder="Enter state">
                        @error('billing_state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="billing_country" class="form-label">Country</label>
                        <input type="text" wire:model="billing_country" id="billing_country" class="form-control form-control-sm border border-2 p-2" placeholder="Enter country">
                        @error('billing_country')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="billing_pin" class="form-label">Zip Code</label>
                        <input type="number" wire:model="billing_pin" id="billing_pin" class="form-control form-control-sm border border-2 p-2" placeholder="Enter PIN">
                        @error('billing_pin')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                  <!-- Shipping Address -->
                <div class="d-flex justify-content-between mt-4">
                    <h6 class="badge bg-danger custom_danger_badge">Customer Shipping Address</h6>
                    <div class="form-check">
                        <input type="checkbox"  wire:change="toggleShippingAddress" wire:model="is_billing_shipping_same" id="isBillingShippingSame" class="form-check-input" @if ($is_billing_shipping_same) checked @endif>
                        <label for="isBillingShippingSame" class="form-check-label"><span class="badge bg-secondary">Shipping address same as billing</span></label>
                    </div>
                </div>
              
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="shipping_address" class="form-label">Street Address</label>
                        <textarea  wire:model="shipping_address" id="shipping_address" class="form-control form-control-sm border border-2 p-2" placeholder="Enter shipping address" @if($is_billing_shipping_same) disabled @endif></textarea>
                        @error('shipping_address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="shipping_landmark" class="form-label">Landmark</label>
                        <input type="text" wire:model="shipping_landmark" id="shipping_landmark" class="form-control form-control-sm border border-2 p-2" placeholder="Enter landmark" @if($is_billing_shipping_same) disabled @endif>
                        @error('shipping_landmark')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="shipping_city" class="form-label">City</label>
                        <input type="text" wire:model="shipping_city" id="shipping_city" class="form-control form-control-sm border border-2 p-2" placeholder="Enter city" @if($is_billing_shipping_same) disabled @endif>
                        @error('shipping_city')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="shipping_state" class="form-label">State</label>
                        <input type="text" wire:model="shipping_state" id="shipping_state" class="form-control form-control-sm border border-2 p-2" placeholder="Enter state" @if($is_billing_shipping_same) disabled @endif>
                        @error('shipping_state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="shipping_country" class="form-label">Country</label>
                        <input type="text" wire:model="shipping_country" id="shipping_country" class="form-control form-control-sm border border-2 p-2" placeholder="Enter country" @if($is_billing_shipping_same) disabled @endif>
                        @error('shipping_country')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="shipping_pin" class="form-label">Zip Code</label>
                        <input type="text" wire:model="shipping_pin" id="shipping_pin" class="form-control form-control-sm border border-2 p-2" placeholder="Enter PIN" @if($is_billing_shipping_same) disabled @endif>
                        @error('shipping_pin')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <h6 class="badge bg-danger custom_danger_badge">Product Information</h6>
                </div>

                <div class="row">
                  <!-- Category -->
                    <div class="mb-3 col-md-4">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" class="form-select form-control-sm border border-2" wire:model="category" wire:change="CatWiseSubCatProduct($event.target.value)" style="padding-left: 20px;">
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
                    <div class="mb-3 col-md-4">
                        <label for="subcategory" class="form-label">Sub-Category</label>
                        <select id="subcategory" class="form-select form-control-sm border border-2" wire:model="selectedSubCategory" wire:change="SubCatWiseProduct($event.target.value)"  style="padding-left: 20px;">
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
                    <div class="mb-3 col-md-4">
                        <label for="product" class="form-label">Product</label>
                        <select id="product" class="form-select form-control-sm border border-2" wire:model="selectedProduct" style="padding-left: 20px;">
                            <option value="" selected hidden>Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedProduct')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

