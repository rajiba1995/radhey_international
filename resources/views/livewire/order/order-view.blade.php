<div>
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <h5 class="mb-0">ORDER #{{$order->order_number}}</h5>
                        <!-- Paid Status -->
                        @if ($order->remaining_amount == 0)
                        <span class="badge bg-success me-2 ms-2 rounded-pill">Paid</span>
                        @else
                        <span class="badge bg-warning me-2 ms-2 rounded-pill">Pending</span>
                        @endif
                        @if ($order->status = 1)
                        <span class="badge bg-info rounded-pill">Ready to Pickup</span>
                        @endif
                    </div>
                    <p class="mt-1 mb-3">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }},
                        <span id="orderYear">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }} (ET)</span>
                    </p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-2">
                    <a href="{{route('admin.order.index')}}" class="btn btn-dark btn-sm mt-3"><i
                            class="material-icons text-white" style="font-size: 15px;">chevron_left</i>
                        Back</a>
                </div>
            </div>

            <!-- Order Details Table -->

            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Order details</h5>
                            {{-- <h6 class="m-0"><a href="javascript:void(0)">Edit</a></h6> --}}
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="w-50 " rowspan="1" colspan="1" style="width: 328px;"
                                            aria-label="products">products</th>
                                        <th class="" rowspan="1" colspan="1" style="width: 65px;"
                                            aria-label="price">price</th>
                                        <th class="" rowspan="1" colspan="1" style="width: 50px;" aria-label="qty">
                                            qty</th>
                                        <th class="" rowspan="1" colspan="1" style="width: 80px;"
                                            aria-label="total">total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($orderItems->isNotEmpty())
                                    @foreach ($orderItems as $item)
                                    {{-- @dd($item); --}}
                                    <tr class="odd">
                                        <td class="">
                                            <div class="d-flex justify-content-start align-items-center product-name">
                                                <div class="me-3">
                                                    @if (!empty($item['product_image']))
                                                    <div class="avatar avatar-sm rounded-2 bg-label-secondary">
                                                        <img src="{{ asset('storage/' . $item['product_image']) }}"
                                                            alt="Product Image" class="rounded-2">
                                                    </div>
                                                    @else
                                                    <div class="avatar avatar-sm rounded-2 bg-label-secondary">
                                                        <img src="{{asset('assets/img/cubes.png')}}"
                                                            alt="Default Image" class="rounded-2">
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="text-nowrap text-heading fw-medium">{{$item['product_name']}}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span>{{number_format($item['price'], 2)}}</span></td>
                                        <td><span>1</span></td>
                                        <td><span>{{number_format($item['price'], 2)}}</span></td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td>
                                            <p>No items found for this order.</p>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                      <td></td>
                                      <td></td>
                                      <td>
                                        <table> 
                                          <tr><td class="text-end"><small>Total Amount:</small></td></tr>
                                          <tr> <td class="text-end"><small>Paid Amount:</small></td></tr>
                                          @if ($order->remaining_amount > 0)
                                          <tr><td class="text-end"><small>Remaining Amount:</small></td></tr>
                                          @endif
                                        </table>
                                      </td>
                                      <td>
                                        <table>
                                          <tr>
                                            
                                            <td><strong>{{number_format($order->total_amount, 2)}}</strong></td>
                                          </tr>
                                          <tr>
                                            <td><strong>{{number_format($order->paid_amount, 2)}}</strong></td>
                                          </tr>
                                            @if ($order->remaining_amount > 0)
                                            <tr>
                                                <td><strong class="text-danger">{{number_format($order->remaining_amount, 2)}}</strong></td>
                                            </tr>
                                            @endif
                                        </table>
                                      </td>
                                  </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                                    @foreach($latestOrders as $latestOrder)
                                    <tr>
                                        <td>
                                            <span class="badge bg-danger custom_danger_badge">
                                                {{ $latestOrder->created_at->format('Y-m-d H:i') }}</span>
                                            <br>
                                           <a href="{{route('admin.order.view',$latestOrder->id)}}">{{ $latestOrder->order_number }}</a>
                                        </td>
                                        <td>{{ $latestOrder->customer? $latestOrder->customer->name : "" }}</td>

                                        <td>{{ number_format($latestOrder->total_amount, 2)  }}</td>
                                        <td class="{{ $latestOrder->remaining_amount > 0 ? "text-danger" : ""}}">
                                            {{ number_format($latestOrder->remaining_amount, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
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
                                    @if ($order->customer && $order->customer->profile_image)
                                    <img src="{{asset($order->customer->profile_image)}}" alt="Avatar"
                                        class="rounded-circle">
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="{{route('admin.customers.details',$order->customer_id)}}">
                                        <h6 class="mb-0">{{$order->customer_name}}</h6>
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex justify-content-start align-items-center mb-4">
                                <div class="shopping-cart">
                                    <i class="ri-shopping-cart-line" style="font-size: 24px; color: green;"></i>
                                </div>
                                <h6 class="text-nowrap mb-0">{{$order->items->count()}} Orders</h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Contact info</h6>
                                <h6 class="mb-1">
                                  <a href="{{route('admin.order.edit',$order->id)}}" class="btn btn-outline-info custom-btn-sm">Edit</a>
                                </h6>
                            </div>
                            <p class="mb-1"><i class="fas fa-envelope" style="font-size: 14px; color: #6c757d;"></i>
                                {{$order->customer_email}}
                            </p>
                            <p class="mb-0"><i class="fas fa-phone" style="font-size: 14px; color: #6c757d;"></i>
                                {{$order->customer? $order->customer->phone: ""}}
                            </p>
                            <p class="mb-0"> <i class="fab fa-whatsapp" style="font-size: 14px; color: #25D366;"></i>
                                {{$order->customer? $order->customer->whatsapp_no: ""}}
                            </p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-1">Shipping address</h5>
                            {{-- <h6 class="m-0"><a href="{{route('admin.order.edit',$order->id)}}">Edit</a></h6> --}}
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{$order->shipping_address}}</p>
                        </div>

                    </div>
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Billing address</h5>
                            {{-- <h6 class="m-0"><a href="{{route('admin.order.edit',$order->id)}}">Edit</a></h6> --}}
                        </div>
                        <div class="card-body">
                            <p class="mb-4">{{$order->billing_address}}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>