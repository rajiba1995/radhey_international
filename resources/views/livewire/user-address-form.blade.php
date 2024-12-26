<div class="container-fluid px-2 px-md-4">
    <div class="card card-body">
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                        <h5 class="mb-3">Customer Information</h5>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('customers.index') }}" class="btn btn-dark btn-sm"> <i class="material-icons text-white" style="font-size: 15px;">chevron_left</i> Back</a>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-3">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <!-- Customer Details -->
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" id="name" class="form-control border border-2 p-2" placeholder="Enter Customer Name">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" wire:model="company_name" id="company_name" class="form-control border border-2 p-2" placeholder="Enter Company Name">
                            @error('company_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="employee_rank" class="form-label">Employee Rank</label>
                            <input type="text" wire:model="employee_rank" class="form-control border border-2 p-2" placeholder="Enter rank">
                            @error('employee_rank')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" wire:model="email" id="email" class="form-control border border-2 p-2" placeholder="Enter Email">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 col-md-3">
                            <label for="dob" class="form-label">Date Of Birth <span class="text-danger">*</span></label>
                            <input type="date" wire:model="dob" id="dob" class="form-control border border-2 p-2">
                            
                            @error('dob')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" wire:model="phone" id="phone" class="form-control border border-2 p-2" placeholder="Enter Phone Number">
                            
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="whatsapp_no" class="form-label">WhatsApp Number <span class="text-danger">*</span></label>
                            <input type="text" wire:model="whatsapp_no" id="whatsapp_no" class="form-control border border-2 p-2" @if($is_wa_same) disabled @endif placeholder="Enter Whatsapp Number">

                            <input type="checkbox" id="is_wa_same" wire:change="SameAsMobile" value="0" @if($is_wa_same) checked @endif>
                            <label for="is_wa_same" class="form-check-label ms-2">Same as Phone Number</label>
                            @error('whatsapp_no')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="image" class="form-label">Profile Image <span class="text-danger">*</span></label>
                            <input type="file" wire:model="image" id="image" class="form-control border border-2 p-2">
                            @if($tempImageUrl)
                               <img src="{{ $tempImageUrl }}" class="img-thumbnail mt-2" width="100">
                            @endif
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="verified_video" class="form-label">Verified Video</label>
                            <input type="file" wire:model="verified_video" id="verified_video" class="form-control border border-2 p-2">
                            
                            @error('verified_video')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mt-4">Address Information</h5>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="billing_address" class="form-label">Billing Address</label>
                            <input type="text" wire:model="billing_address" id="billing_address" class="form-control border border-2 p-2" placeholder="Enter billing address">
                            @error('billing_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="billing_landmark" class="form-label">Billing Landmark</label>
                            <input type="text" wire:model="billing_landmark" id="billing_landmark" class="form-control border border-2 p-2" placeholder="Enter landmark">
                            @error('billing_landmark')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="billing_city" class="form-label">Billing City</label>
                            <input type="text" wire:model="billing_city" id="billing_city" class="form-control border border-2 p-2" placeholder="Enter city">
                            @error('billing_city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="billing_state" class="form-label">Billing State</label>
                            <input type="text" wire:model="billing_state" id="billing_state" class="form-control border border-2 p-2" placeholder="Enter state">
                            @error('billing_state')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="billing_country" class="form-label">Billing Country</label>
                            <input type="text" wire:model="billing_country" id="billing_country" class="form-control border border-2 p-2" placeholder="Enter country">
                            @error('billing_country')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="billing_pin" class="form-label">Billing PIN</label>
                            <input type="number" wire:model="billing_pin" id="billing_pin" class="form-control border border-2 p-2" placeholder="Enter PIN">
                            @error('billing_pin')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox"  wire:change="toggleShippingAddress" wire:model="is_billing_shipping_same" id="isBillingShippingSame" class="form-check-input" @if ($is_billing_shipping_same) checked @endif>
                        <label for="isBillingShippingSame" class="form-check-label">Shipping address same as billing</label>
                    </div>
                    <!-- Shipping Address -->
                  
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <input type="text" wire:model="shipping_address" id="shipping_address" class="form-control border border-2 p-2" placeholder="Enter shipping address" @if($is_billing_shipping_same) disabled @endif>
                            @error('shipping_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="shipping_landmark" class="form-label">Shipping Landmark</label>
                            <input type="text" wire:model="shipping_landmark" id="shipping_landmark" class="form-control border border-2 p-2" placeholder="Enter landmark" @if($is_billing_shipping_same) disabled @endif>
                            @error('shipping_landmark')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="shipping_city" class="form-label">Shipping City</label>
                            <input type="text" wire:model="shipping_city" id="shipping_city" class="form-control border border-2 p-2" placeholder="Enter city" @if($is_billing_shipping_same) disabled @endif>
                            @error('shipping_city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="shipping_state" class="form-label">Shipping State</label>
                            <input type="text" wire:model="shipping_state" id="shipping_state" class="form-control border border-2 p-2" placeholder="Enter state" @if($is_billing_shipping_same) disabled @endif>
                            @error('shipping_state')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="shipping_country" class="form-label">Shipping Country</label>
                            <input type="text" wire:model="shipping_country" id="shipping_country" class="form-control border border-2 p-2" placeholder="Enter country" @if($is_billing_shipping_same) disabled @endif>
                            @error('shipping_country')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="shipping_pin" class="form-label">Shipping PIN</label>
                            <input type="text" wire:model="shipping_pin" id="shipping_pin" class="form-control border border-2 p-2" placeholder="Enter PIN" @if($is_billing_shipping_same) disabled @endif>
                            @error('shipping_pin')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mt-4">Account information</h5>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="gst_number" class="form-label">GST Number</label>
                            <input type="text" wire:model="gst_number" id="gst_number" class="form-control border border-2 p-2" placeholder="Enter GST Number">
                            @error('gst_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="gst_certificate_image" class="form-label">GST Certificate Image</label>
                            <input type="file" wire:model="gst_certificate_image" id="gst_certificate_image" class="form-control border border-2 p-2">
                            @error('gst_certificate_image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="credit_limit" class="form-label">Credit Limit</label>
                            <input type="number" wire:model="credit_limit" id="credit_limit" class="form-control border border-2 p-2" placeholder="Enter Credit Limit">
                            @error('credit_limit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="credit_days" class="form-label">Credit Days</label>
                            <input type="number" wire:model="credit_days" id="credit_days" class="form-control border border-2 p-2" placeholder="Enter Credit Days">
                            @error('credit_days')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-dark mt-3">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
