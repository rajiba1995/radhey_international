<div>
    <h4>Customer Details :-</h4>
    {{-- <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item"><a href="#">Customer Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Details</li>
        </ol>
    </nav> --}}
    <div class="card mt-3">
        <div class="row">
            <div class="col-12">
                <div class=" my-4">
                    <div class="card-header">
                        <h4>{{ ucwords($customer->name) }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Business Name:</strong> {{ $customer->company_name }}</p>
                                <p><strong>Contact Details:</strong> {{ $customer->phone }}</p>
                                <p><strong>WhatsApp Details:</strong> {{ $customer->phone }}</p>
                                <p><strong>Published:</strong> {{ date('d/m/Y') }}</p>
                                <p><strong>Address Outstation:</strong> {{ $customer->is_outstation ? 'Yes' : 'No' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>GST Number:</strong> {{ $customer->gst_number }}</p>
                                <p><strong>Credit Limit:</strong> {{ $customer->credit_limit }}</p>
                                <p><strong>Credit Days:</strong> {{ $customer->credit_days }}</p>
                                <p><strong>GST Certificate:</strong></p>
                                @if ($customer->gst_certificate_image)
                                    <img src="{{ asset($customer->gst_certificate_image) }}" alt="GST Certificate" class="img-thumbnail" width="200">
                                @else
                                    <p>No GST certificate uploaded.</p>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Billing Address</h6>
                                @php
                                    $billingAddress = $customer->address->firstWhere('address_type', '1');
                                @endphp
                                @if ($billingAddress)
                                    <p><strong>Address:</strong> {{ $billingAddress->address }}</p>
                                    <p><strong>Landmark:</strong> {{ $billingAddress->landmark }}</p>
                                    <p><strong>City:</strong> {{ $billingAddress->city }}</p>
                                    <p><strong>State:</strong> {{ $billingAddress->state }}</p>
                                    <p><strong>Country:</strong> {{ $billingAddress->country }}</p>
                                    <p><strong>Pincode:</strong> {{ $billingAddress->zip_code }}</p>
                                @else
                                    <p>No billing address available.</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6>Shipping Address</h6>
                                @php
                                    $shippingAddress = $customer->address->firstWhere('address_type', '2');
                                @endphp
                                @if ($shippingAddress)
                                    <p><strong>Address:</strong> {{ $shippingAddress->address }}</p>
                                    <p><strong>Landmark:</strong> {{ $shippingAddress->landmark }}</p>
                                    <p><strong>City:</strong> {{ $shippingAddress->city }}</p>
                                    <p><strong>State:</strong> {{ $shippingAddress->state }}</p>
                                    <p><strong>Country:</strong> {{ $shippingAddress->country }}</p>
                                    <p><strong>Pincode:</strong> {{ $shippingAddress->zip_code }}</p>
                                @else
                                    <p>No shipping address available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="{{route('customers.index')}}" class="btn btn-danger btn-sm mt-3">Back to Customer</a>
</div>
