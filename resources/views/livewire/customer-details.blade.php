<div>
    <div class="content-wrapper">
        <section class="admin__title">
            <h5> Customer Details</h5>
        </section>
        <section>
            <ul class="breadcrumb_menu">
                <li><a href="{{ route('customers.index') }}">Customers</a></li>
                <li>Customer Details</li>
                <li class="back-button">
                <a class="btn btn-dark btn-sm text-decoration-none text-light font-weight-bold mb-0" href="{{ route('customers.index') }}" role="button">
                    <i class="material-icons text-white" style="font-size: 15px;">chevron_left</i>
                    <span class="ms-1">Back</span>
                </a>
                </li>
            </ul>
        </section>
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <!-- Paid Status -->
                        <!-- <span class="badge bg-warning me-2 ms-2 rounded-pill">Pending</span> -->
                        <!-- <span class="badge bg-info rounded-pill">Ready to Pickup</span> -->
                    </div>
                    <p class="mt-1 mb-3">
                       <!-- ccsc</span> -->
                    </p>
                </div>
                
            </div>

            <!-- Order Details Table -->

            <div class="row">

                <div class="col-12 col-lg-8">
                    <div class="card mb-4" style="height: 526px;">
                        <div class="card-body">
                            <!-- <h5 class="card-title mb-4">Customer details</h5> -->
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-1">Customer details</h5>
                                <h6 class="mb-1">
                                  <a href="{{ route('admin.customers.edit', ['id' => $customer->id]) }}" class="btn btn-outline-info custom-btn-sm">Edit</a>
                                </h6>
                            </div>
                            <div class="d-flex justify-content-start align-items-center mb-4">
                                <div class="avatar me-3">
                                    @if ($customer && $customer->profile_image)
                                    <img src="{{asset($customer->profile_image)}}" alt="Avatar"
                                        class="rounded-circle">
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="">
                                        <h6 class="mb-0">{{$customer->name}}</h6>
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex justify-content-start align-items-center mb-4">
                                <div class="shopping-cart">
                                    <i class="ri-shopping-cart-line" style="font-size: 24px; color: green;"></i>
                                </div>
                                <h6 class="text-nowrap mb-0">{{$customer->ordersAsCustomer->count()}} Orders</h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Contact info</h6>
                            </div>
                            <p class="mb-1"><i class="fas fa-envelope" style="font-size: 14px; color: #6c757d;"></i>
                                {{$customer->email}}
                            </p>
                            <p class="mb-0"> <i class="fa fa-building" style="font-size: 14px; color: #6c757d;"></i>
                            {{$customer->company_name}}
                            </p>
                            <p class="mb-0"><i class="fas fa-phone" style="font-size: 14px; color: #6c757d;"></i>
                            {{$customer->phone}}
                            </p>
                            <p class="mb-0"> <i class="fab fa-whatsapp" style="font-size: 14px; color: #25D366;"></i>
                            {{$customer->whatsapp_no}}
                            </p>
                            <p class="mb-0"> <i class="fa fa-birthday-cake" style="font-size: 14px; color: #6c757d;"></i>
                            {{$customer->dob}}
                            </p>
                            <p class="mb-0"> <i class="fa fa-trophy" style="font-size: 14px; color: #6c757d;"></i>
                            {{$customer->employee_rank}}
                            </p>
                            
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body  p-3">
                        <h5 class="card-title mb-0">Shipping address</h5>
                            @if($customer->shippingAddressLatest)
                                <p>{{ $customer->shippingAddressLatest->address }},{{ $customer->shippingAddressLatest->landmark }},{{ $customer->shippingAddressLatest->city }}, {{ $customer->shippingAddressLatest->state }},{{ $customer->shippingAddressLatest->country }} - {{ $customer->shippingAddressLatest->zip_code }}</p>
                            @else
                                <p>No shipping address available.</p>
                            @endif
                        </div>
                    </div>

                    <div class="card mb-4">
                        
                        <div class="card-body p-3">
                        <h5 class="card-title">Billing address</h5>

                            @if($customer->billingAddressLatest)
                            <p>{{ $customer->billingAddressLatest->address }},{{ $customer->billingAddressLatest->landmark }},{{ $customer->billingAddressLatest->city }}, {{ $customer->billingAddressLatest->state }},{{ $customer->billingAddressLatest->country }} - {{ $customer->billingAddressLatest->zip_code }}</p>
                            @else
                                <p>No billing address available.</p>
                            @endif
                        </div>

                    </div>
                    <div class="card">
                        <div class="card-body">
                        <h5 class="card-title">Account Information</h5>
                         @if($customer->gst_certificate_image !=""||$customer->gst_number !=""||$customer->credit_limit !=""||$customer->credit_days !="")
                            <div class="avatar me-3">
                                @if ($customer->gst_certificate_image)
                                <img src="{{asset($customer->gst_certificate_image)}}" alt="Avatar"
                                    class="rounded-circle">
                                @endif
                            </div>
                                @if($customer->gst_number)
                            <p class="mb-1"><i class="fas fa-id-card" style="font-size: 14px; color: #6c757d;"></i>
                                {{$customer->gst_number}}
                            </p>
                                @endif 
                                @if($customer->credit_limit)
                            <p class="mb-1"><i class="fas fa-credit-card" style="font-size: 14px; color: #6c757d;"></i>
                                {{$customer->credit_limit}}
                            </p>
                            @endif 
                            @if($customer->credit_days)
                            <p class="mb-1"><i class="fas fa-calendar-day" style="font-size: 14px; color: #6c757d;"></i>
                                {{$customer->credit_days}}
                            </p>
                            @endif 

                            @else
                        <div class="card-body">
                            <p class="mb-1 text-muted">
                                <i class="fas fa-info-circle" style="font-size: 14px; color: #6c757d;"></i>
                                No information found.
                            </p>
                        </div>
                        @endif
                        </div>
                    </div>
                  
                </div>



                <div class="col-12 col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Latest 10 Orders</h5>
                        </div>
                        <div class="card-body">
                            <!-- Add table-responsive for responsiveness -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer Name</th>
                                            <th>Billing Amount</th>
                                            <th>Remaining Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($latestOrders as $latestOrder)
                                        <tr>
                                            <td>
                                                <span class="badge bg-danger custom_danger_badge">
                                                    {{ $latestOrder->updated_at }}
                                                </span>
                                                <br>
                                                <a href="{{ route('admin.order.view', $latestOrder->id) }}" class="text-primary">
                                                    {{ $latestOrder->order_number }}
                                                </a>
                                            </td>
                                            <td>{{ $latestOrder->customer->name }}</td>
                                            <td>{{ number_format($latestOrder->total_amount, 2) }}</td>
                                            <td class="{{ $latestOrder->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($latestOrder->remaining_amount, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

               
            </div>
        </div>
    </div>
</div>