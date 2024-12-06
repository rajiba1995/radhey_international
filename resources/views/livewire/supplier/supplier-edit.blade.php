<div class="container-fluid px-2 px-md-4">
    <div class="card card-body">
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                        <h5 class="mb-3">Edit Supplier Information</h5>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ url('admin/suppliers') }}" class="btn btn-primary">Back</a>
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

                <form wire:submit.prevent="updateSupplier">
                    <div class="row">
                        <!-- Supplier Details -->
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" wire:model.defer="name" id="name" class="form-control border border-2 p-2">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" wire:model.defer="email" id="email" class="form-control border border-2 p-2">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="text" wire:model.defer="mobile" id="mobile" class="form-control border border-2 p-2">
                            @error('mobile') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="whatsapp_no" class="form-label">WhatsApp Number</label>
                            <input type="text" wire:model.defer="whatsapp_no" id="whatsapp_no" class="form-control border border-2 p-2">
                            @error('whatsapp_no') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="gst_number" class="form-label">GST Number</label>
                            <input type="text" wire:model.defer="gst_number" id="gst_number" class="form-control border border-2 p-2">
                            @error('gst_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="gst_file" class="form-label">GST File</label>
                            <input type="file" id="gst_file" wire:model.defer="gst_file" class="form-control border border-2 p-2">
                            @if ($existingGstFile)
                                <p>Current File: <a href="{{ asset('storage/' . $existingGstFile) }}" target="_blank">View</a></p>
                            @endif
                            @error('gst_file') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="credit_limit" class="form-label">Credit Limit</label>
                            <input type="number" wire:model.defer="credit_limit" id="credit_limit" class="form-control border border-2 p-2">
                            @error('credit_limit') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="credit_days" class="form-label">Credit Days</label>
                            <input type="number" wire:model.defer="credit_days" id="credit_days" class="form-control border border-2 p-2">
                            @error('credit_days') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <h5 class="mt-4">Billing Address</h5>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="billing_address" class="form-label">Address</label>
                            <input type="text" wire:model.defer="billing_address" id="billing_address" class="form-control border border-2 p-2">
                            @error('billing_address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="billing_landmark" class="form-label">Landmark</label>
                            <input type="text" wire:model.defer="billing_landmark" id="billing_landmark" class="form-control border border-2 p-2">
                            @error('billing_landmark') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="billing_city" class="form-label">City</label>
                            <input type="text" wire:model.defer="billing_city" id="billing_city" class="form-control border border-2 p-2">
                            @error('billing_city') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="billing_state" class="form-label">State</label>
                            <input type="text" wire:model.defer="billing_state" id="billing_state" class="form-control border border-2 p-2">
                            @error('billing_state') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="billing_pin" class="form-label">PIN</label>
                            <input type="text" wire:model.defer="billing_pin" id="billing_pin" class="form-control border border-2 p-2">
                            @error('billing_pin') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="billing_country" class="form-label">Country</label>
                            <input type="text" wire:model.defer="billing_country" id="billing_country" class="form-control border border-2 p-2">
                            @error('billing_country') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <h5 class="mt-4">Shipping Address</h5>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="shipping_address" class="form-label">Address</label>
                            <input type="text" id="shipping_address" wire:model.defer="shipping_address" class="form-control border border-2 p-2">
                            @error('shipping_address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="shipping_landmark" class="form-label">Landmark</label>
                            <input type="text" id="shipping_landmark" wire:model.defer="shipping_landmark" class="form-control border border-2 p-2">
                            @error('shipping_landmark') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="shipping_city" class="form-label">City</label>
                            <input type="text" id="shipping_city" wire:model.defer="shipping_city" class="form-control border border-2 p-2">
                            @error('shipping_city') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="shipping_state" class="form-label">State</label>
                            <input type="text" id="shipping_state" wire:model.defer="shipping_state" class="form-control border border-2 p-2">
                            @error('shipping_state') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="shipping_pin" class="form-label">Pin Code</label>
                            <input type="text" id="shipping_pin" wire:model.defer="shipping_pin" class="form-control border border-2 p-2">
                            @error('shipping_pin') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="shipping_country" class="form-label">Country</label>
                            <input type="text" id="shipping_country" wire:model.defer="shipping_country" class="form-control border border-2 p-2">
                            @error('shipping_country') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>


                    <button type="submit" class="btn bg-gradient-dark mt-3">Update Supplier</button>
                </form>
            </div>
        </div>
    </div>
</div>
