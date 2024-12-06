<div>
    <h4>Supplier Details</h4>
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="table-responsive p-0">
                    <div class="card-header">
                        <h3>{{ $supplier->name }}</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Email:</strong> {{ $supplier->email }}</p>
                        <p><strong>Mobile:</strong> {{ $supplier->mobile }}</p>
                        <p><strong>WhatsApp Number:</strong> 
                            {{ $supplier->is_wa_same ? $supplier->mobile : $supplier->whatsapp_no }}
                        </p>
                        <p><strong>GST Number:</strong> {{ $supplier->gst_number }}</p>
                        <p><strong>Credit Limit:</strong> {{ $supplier->credit_limit }}</p>
                        <p><strong>Credit Days:</strong> {{ $supplier->credit_days }}</p>
                        <p><strong>Status:</strong> {{ $supplier->status == 1 ? 'Active' : 'Inactive' }}</p>

                        <!-- Billing Address Details -->
                        <h6>Billing Address:</h6>
                        <p><strong>Address:</strong> {{ $supplier->billing_address }}</p>
                        <p><strong>Landmark:</strong> {{ $supplier->billing_landmark }}</p>
                        <p><strong>City:</strong> {{ $supplier->billing_city }}</p>
                        <p><strong>State:</strong> {{ $supplier->billing_state }}</p>
                        <p><strong>Country:</strong> {{ $supplier->billing_country }}</p>
                        <p><strong>Pin Code:</strong> {{ $supplier->billing_pin }}</p>

                        <!-- Shipping Address Details -->
                        <h6>Shipping Address:</h6>
                        @if ($supplier->is_billing_shipping_same)
                            <p>Same as Billing Address</p>
                        @else
                            <p><strong>Address:</strong> {{ $supplier->shipping_address }}</p>
                            <p><strong>Landmark:</strong> {{ $supplier->shipping_landmark }}</p>
                            <p><strong>City:</strong> {{ $supplier->shipping_city }}</p>
                            <p><strong>State:</strong> {{ $supplier->shipping_state }}</p>
                            <p><strong>Country:</strong> {{ $supplier->shipping_country }}</p>
                            <p><strong>Pin Code:</strong> {{ $supplier->shipping_pin }}</p>
                        @endif

                        <!-- GST Certificate -->
                        @if ($supplier->gst_file)
                            <h6>GST Certificate:</h6>
                            <img src="{{ asset('storage/' . $supplier->gst_file) }}" alt="GST Certificate" class="img-thumbnail" width="200">
                        @else
                            <p>No GST certificate uploaded.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('suppliers.index') }}" class="btn btn-primary mt-3">Back to List</a>
</div>
