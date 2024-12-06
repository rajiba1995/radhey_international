<div class="container-fluid px-2 px-md-4">
    <div class="card card-body">
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                        <h5 class="mb-3">Customer Information</h5>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('customers.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-3">
                
                <form wire:submit.prevent="save">
                    <div class="row">
                        <!-- Customer Details -->
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" wire:model="name" id="name" class="form-control border border-2 p-2">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" wire:model="company_name" id="company_name" class="form-control border border-2 p-2">
                            @error('company_name')
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
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" wire:model="phone" id="phone" class="form-control border border-2 p-2">
                            @error('phone')
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
                            <label for="gst_certificate_image" class="form-label">GST Certificate Image</label>
                            <input type="file" wire:model="gst_certificate_image" id="gst_certificate_image" class="form-control border border-2 p-2">
                            @error('gst_certificate_image')
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
                    </div>

                    <h5 class="mt-4">Address Information</h5>

                    <div class="row">
                        <!-- Address Details -->
                        <div class="mb-3 col-md-6">
                            <label for="address_type" class="form-label">Address Type</label>
                            <input type="text" wire:model="address_type" id="address_type" class="form-control border border-2 p-2">
                            @error('address_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" wire:model="address" id="address" class="form-control border border-2 p-2">
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="landmark" class="form-label">Landmark</label>
                            <input type="text" wire:model="landmark" id="landmark" class="form-control border border-2 p-2">
                            @error('landmark')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" wire:model="city" id="city" class="form-control border border-2 p-2">
                            @error('city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="state" class="form-label">State</label>
                            <input type="text" wire:model="state" id="state" class="form-control border border-2 p-2">
                            @error('state')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" wire:model="country" id="country" class="form-control border border-2 p-2">
                            @error('country')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="zip_code" class="form-label">Zip Code</label>
                            <input type="text" wire:model="zip_code" id="zip_code" class="form-control border border-2 p-2">
                            @error('zip_code')
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
