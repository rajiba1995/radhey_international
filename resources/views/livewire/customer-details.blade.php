<div>
    <h4>Customer Details</h4>
    <!-- <div class="card mt-3"> -->
    <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="table-responsive p-0">
                        <div class="card-header">
                            <h3>{{ $customer->name }}</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Email:</strong> {{ $customer->email }}</p>
                            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                            <p><strong>Company Name:</strong> {{ $customer->company_name }}</p>
                            <p><strong>GST Number:</strong> {{ $customer->gst_number }}</p>
                            <p><strong>Credit Limit:</strong> {{ $customer->credit_limit }}</p>
                            <p><strong>Credit Days:</strong> {{ $customer->credit_days }}</p>

                            <!-- Address Details -->
                            @if ($customer->address)
                            <h6>Address Details:</h6>
                            <p><strong>Address:</strong> {{ $customer->address->address }}</p>
                            <p><strong>City:</strong> {{ $customer->address->city }}</p>
                            <p><strong>State:</strong> {{ $customer->address->state }}</p>
                            <p><strong>Country:</strong> {{ $customer->address->country }}</p>
                            <p><strong>Zip Code:</strong> {{ $customer->address->zip_code }}</p>
                            @else
                            <p>No address available.</p>
                            @endif

                            <!-- GST Certificate -->
                            @if ($customer->gst_certificate_image)
                            <h6>GST Certificate:</h6>
                            <img src="{{ asset('storage/' . $customer->gst_certificate_image) }}" alt="GST Certificate" class="img-thumbnail" width="200">
                            @else
                            <p>No GST certificate uploaded.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- </div> -->

    <a href="{{ route('customers.index') }}" class="btn btn-primary mt-3">Back to List</a>
</div>

