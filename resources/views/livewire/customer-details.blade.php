<div>
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <!-- Paid Status -->
                        <span class="badge bg-success me-2 ms-2 rounded-pill">{{$customer->name}}</span>
                        <!-- <span class="badge bg-warning me-2 ms-2 rounded-pill">Pending</span> -->
                        <!-- <span class="badge bg-info rounded-pill">Ready to Pickup</span> -->
                    </div>
                    <p class="mt-1 mb-3">
                       <!-- ccsc</span> -->
                    </p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-2">
                    <a href="" class="btn btn-dark btn-sm mt-3"><i
                            class="material-icons text-white" style="font-size: 15px;">chevron_left</i>
                        Back</a>
                </div>
            </div>

            <!-- Order Details Table -->

            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Latest 5 Orders</h5>
                        </div>
                        <div class="card-body ">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer Name</th>
                                        <th>Billing Amount</th>
                                        <th>Remaining Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach($latestOrders as $latestOrder) --}}
                                    <tr>
                                        <td>
                                            <span class="badge bg-danger custom_danger_badge">
                                               62626f</span>
                                            <br>
                                           <a href="">411155f</a>
                                        </td>
                                        <td>Souvik</td>

                                        <td>10000</td>
                                        <td class="">
                                           200
                                        </td>
                                    </tr>
                                    {{-- @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Customer details</h5>

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
                                <h6 class="text-nowrap mb-0">1 Orders</h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Contact info</h6>
                                <h6 class="mb-1">
                                  <a href="{{ route('admin.customers.edit', ['id' => $customer->id]) }}" class="btn btn-outline-info custom-btn-sm">Edit</a>
                                </h6>
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

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-1">Shipping address</h5>
                            {{-- <h6 class="m-0"><a href="{{route('admin.order.edit',$order->id)}}">Edit</a></h6> --}}
                        </div>
                        <div class="card-body">
                        @if($customer->shippingAddressLatest)
                            <p>{{ $customer->shippingAddressLatest->address }},{{ $customer->shippingAddressLatest->landmark }},{{ $customer->shippingAddressLatest->city }}, {{ $customer->shippingAddressLatest->state }},{{ $customer->shippingAddressLatest->country }} - {{ $customer->shippingAddressLatest->zip_code }}</p>
                        @else
                            <p>No shipping address available.</p>
                        @endif
                        </div>

                    </div>
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Billing address</h5>
                            {{-- <h6 class="m-0"><a href="{{route('admin.order.edit',$order->id)}}">Edit</a></h6> --}}
                        </div>
                        <div class="card-body">
                            @if($customer->billingAddressLatest)
                            <p>{{ $customer->billingAddressLatest->address }},{{ $customer->billingAddressLatest->landmark }},{{ $customer->billingAddressLatest->city }}, {{ $customer->billingAddressLatest->state }},{{ $customer->billingAddressLatest->country }} - {{ $customer->billingAddressLatest->zip_code }}</p>
                            @else
                                <p>No billing address available.</p>
                            @endif
                        </div>

                    </div>
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Account Information</h5>
                            {{-- <h6 class="m-0"><a href="{{route('admin.order.edit',$order->id)}}">Edit</a></h6> --}}
                        </div>
                        <div class="card-body">
                        {{-- @if($customer->gst_number)--}}
                            <div class="avatar me-3">
                                @if ($customer->gst_certificate_image)
                                <img src="{{asset($customer->gst_certificate_image)}}" alt="Avatar"
                                    class="rounded-circle">
                                @endif
                            </div>
                            <p class="mb-1"><i class="fas fa-id-card" style="font-size: 14px; color: #6c757d;"></i>
                                {{$customer->gst_number}}
                            </p>
                            {{--  @endif--}}
                           
                            <p class="mb-1"><i class="fas fa-credit-card" style="font-size: 14px; color: #6c757d;"></i>
                                {{$customer->credit_limit}}
                            </p>
                            <p class="mb-1"><i class="fas fa-calendar-day" style="font-size: 14px; color: #6c757d;"></i>
                                {{$customer->credit_days}}
                            </p>
                           
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>