<div class="container-fluid px-2 px-md-4">
    <div class="card card-body">
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                        <h5 class="mb-3">Supplier Information</h5>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-3">
                
                <form wire:submit.prevent="save">
                    <div class="row">
                        <!-- Supplier Details -->
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Supplier Name</label>
                            <input type="text" wire:model="name" id="name" class="form-control border border-2 p-2">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" wire:model="email" id="email" class="form-control border border-2 p-2">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="text" wire:model="mobile" id="mobile" class="form-control border border-2 p-2">
                            @error('mobile')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="whatsapp_no" class="form-label">WhatsApp Number</label>
                            <input type="text" wire:model="whatsapp_no" id="whatsapp_no" class="form-control border border-2 p-2">
                            @error('whatsapp_no')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="gst_number" class="form-label">GST Number</label>
                            <input type="text" wire:model="gst_number" id="gst_number" class="form-control border border-2 p-2">
                            @error('gst_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="gst_file" class="form-label">GST File</label>
                            <input type="file" wire:model="gst_file" id="gst_file" class="form-control border border-2 p-2">
                            @error('gst_file')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="credit_limit" class="form-label">Credit Limit</label>
                            <input type="number" wire:model="credit_limit" id="credit_limit" class="form-control border border-2 p-2">
                            @error('credit_limit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="credit_days" class="form-label">Credit Days</label>
                            <input type="number" wire:model="credit_days" id="credit_days" class="form-control border border-2 p-2">
                            @error('credit_days')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select wire:model="status" id="status" class="form-control border border-2 p-2">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mt-4">Billing Address Information</h5>

                    <div class="row">
                        <!-- Billing Address -->
                        <div class="mb-3 col-md-6">
                            <label for="billing_address" class="form-label">Billing Address</label>
                            <input type="text" wire:model="billing_address" id="billing_address" class="form-control border border-2 p-2">
                            @error('billing_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="billing_landmark" class="form-label">Billing Landmark</label>
                            <input type="text" wire:model="billing_landmark" id="billing_landmark" class="form-control border border-2 p-2">
                            @error('billing_landmark')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="billing_city" class="form-label">Billing City</label>
                            <input type="text" wire:model="billing_city" id="billing_city" class="form-control border border-2 p-2">
                            @error('billing_city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="billing_state" class="form-label">Billing State</label>
                            <input type="text" wire:model="billing_state" id="billing_state" class="form-control border border-2 p-2">
                            @error('billing_state')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="billing_country" class="form-label">Billing Country</label>
                            <input type="text" wire:model="billing_country" id="billing_country" class="form-control border border-2 p-2">
                            @error('billing_country')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="billing_pin" class="form-label">Billing Pin</label>
                            <input type="text" wire:model="billing_pin" id="billing_pin" class="form-control border border-2 p-2">
                            @error('billing_pin')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- <div class="mb-3 col-md-6">
                            <label for="is_billing_shipping_same" class="form-label">Billing and Shipping Address Same</label>
                            <select wire:model="is_billing_shipping_same" id="is_billing_shipping_same" class="form-control border border-2 p-2">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                           
                        </div> -->
                    </div>

                    <h5 class="mt-4">Shipping Address Information</h5>

                    <div class="row">
                        <!-- Shipping Address -->
                        <div class="mb-3 col-md-6">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <input type="text" wire:model="shipping_address" id="shipping_address" class="form-control border border-2 p-2">
                            @error('shipping_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="shipping_landmark" class="form-label">Shipping Landmark</label>
                            <input type="text" wire:model="shipping_landmark" id="shipping_landmark" class="form-control border border-2 p-2">
                            @error('shipping_landmark')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="shipping_city" class="form-label">Shipping City</label>
                            <input type="text" wire:model="shipping_city" id="shipping_city" class="form-control border border-2 p-2">
                            @error('shipping_city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="shipping_state" class="form-label">Shipping State</label>
                            <input type="text" wire:model="shipping_state" id="shipping_state" class="form-control border border-2 p-2">
                            @error('shipping_state')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="shipping_country" class="form-label">Shipping Country</label>
                            <input type="text" wire:model="shipping_country" id="shipping_country" class="form-control border border-2 p-2">
                            @error('shipping_country')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="shipping_pin" class="form-label">Shipping Pin</label>
                            <input type="text" wire:model="shipping_pin" id="shipping_pin" class="form-control border border-2 p-2">
                            @error('shipping_pin')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-4">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
