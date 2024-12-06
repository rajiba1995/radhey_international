<div class="container-fluid px-2 px-md-4">
    <div class="card card-body">
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                        <h5 class="mb-3">Edit Customer Information</h5>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ url('admin/customers') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>

            <div class="card-body p-3">
                <!-- Display success message -->
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif


                <!-- Form to update customer and address details -->
                <form wire:submit.prevent="updateCustomer">
                    <div class="row">
                        <!-- User Details -->
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" wire:model="name" id="name" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" wire:model="company_name" id="company_name" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" wire:model="email" id="email" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" wire:model="phone" id="phone" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="whatsapp_no" class="form-label">WhatsApp Number</label>
                            <input type="text" wire:model="whatsapp_no" id="whatsapp_no" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="gst_number" class="form-label">GST Number</label>
                            <input type="text" wire:model="gst_number" id="gst_number" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="credit_limit" class="form-label">Credit Limit</label>
                            <input type="number" wire:model="credit_limit" id="credit_limit" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="credit_days" class="form-label">Credit Days</label>
                            <input type="number" wire:model="credit_days" id="credit_days" class="form-control border border-2 p-2">
                        </div>
                        
                        <!-- GST Certificate Image -->
                        <div class="form-group">
                            <label for="gst_certificate_image">GST Certificate Image</label>

                            @if ($existingGstCertificateImage)
                                <div>
                                    <!-- Display the existing image -->
                                    <img src="{{ asset('storage/' . str_replace('public/', '', $existingGstCertificateImage)) }}" 
                                        alt="GST Certificate Image" 
                                        class="img-thumbnail" 
                                        width="100">
                                    <p>Current Image</p>
                                </div>
                            @endif

                            <!-- File input for uploading a new image -->
                            <input type="file" id="gst_certificate_image" wire:model="gst_certificate_image" class="form-control">
                            @error('gst_certificate_image') 
                                <span class="text-danger">{{ $message }}</span> 
                            @enderror
                        </div>

                    </div>

                    <h5 class="mt-4">Address Information</h5>

                    <div class="row">
                        <!-- Address Details -->
                        <div class="mb-3 col-md-6">
                            <label for="address_type" class="form-label">Address Type</label>
                            <input type="text" wire:model="address_type" id="address_type" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" wire:model="address" id="address" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="landmark" class="form-label">Landmark</label>
                            <input type="text" wire:model="landmark" id="landmark" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" wire:model="city" id="city" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="state" class="form-label">State</label>
                            <input type="text" wire:model="state" id="state" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" wire:model="country" id="country" class="form-control border border-2 p-2">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="zip_code" class="form-label">Zip Code</label>
                            <input type="text" wire:model="zip_code" id="zip_code" class="form-control border border-2 p-2">
                        </div>
                    </div>

                    <button type="submit" class="btn bg-gradient-dark mt-3">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
