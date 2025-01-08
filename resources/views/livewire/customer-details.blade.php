<div>
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <h5 class="mb-0">ORDER #65555</h5>
                        <!-- Paid Status -->
                        {{-- @if ($order->remaining_amount == 0) --}}
                        <span class="badge bg-success me-2 ms-2 rounded-pill">Paid</span>
                        {{-- @else --}}
                        <span class="badge bg-warning me-2 ms-2 rounded-pill">Pending</span>
                        {{-- @endif --}}
                        {{-- @if ($order->status = 1) --}}
                        <span class="badge bg-info rounded-pill">Ready to Pickup</span>
                        {{-- @endif --}}
                    </div>
                    <p class="mt-1 mb-3">
                       ccsc</span>
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
                                    {{-- @if ($order->customer && $order->customer->profile_image) --}}
                                    <img src="" alt="Avatar"
                                        class="rounded-circle">
                                    {{-- @endif --}}
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="">
                                        <h6 class="mb-0">csssdssd</h6>
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
                                  <a href="" class="btn btn-outline-info custom-btn-sm">Edit</a>
                                </h6>
                            </div>
                            <p class="mb-1"><i class="fas fa-envelope" style="font-size: 14px; color: #6c757d;"></i>
                                dffd
                            </p>
                            <p class="mb-0"><i class="fas fa-phone" style="font-size: 14px; color: #6c757d;"></i>
                               dffdfdf
                            </p>
                            <p class="mb-0"> <i class="fab fa-whatsapp" style="font-size: 14px; color: #25D366;"></i>
                               dfdfdfdf
                            </p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-1">Shipping address</h5>
                            {{-- <h6 class="m-0"><a href="{{route('admin.order.edit',$order->id)}}">Edit</a></h6> --}}
                        </div>
                        <div class="card-body">
                            <p class="mb-0">dfdfdf</p>
                        </div>

                    </div>
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Billing address</h5>
                            {{-- <h6 class="m-0"><a href="{{route('admin.order.edit',$order->id)}}">Edit</a></h6> --}}
                        </div>
                        <div class="card-body">
                            <p class="mb-4">fdfdfdf</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>