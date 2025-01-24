<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="block-heading m-0">Purchase Order Detail</h4>
        <a href="{{route('purchase_order.index')}}" class="btn btn-cta btn-sm">
        <i class="material-icons text-white" style="font-size: 15px;">chevron_left</i>
        Back</a>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-xs font-weight-bold mb-1">Order ID:</p>
                            <p>{{$purchaseOrder->unique_id}}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-xs font-weight-bold mb-1">Supplier:</p>
                            <p>{{$purchaseOrder->supplier ? $purchaseOrder->supplier->name : ""}}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-xs font-weight-bold mb-1">Contact:</p>
                            <p>{{$purchaseOrder->supplier ? $purchaseOrder->supplier->mobile : ""}}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-xs font-weight-bold mb-1">Email:</p>
                            <p>{{$purchaseOrder->supplier ? $purchaseOrder->supplier->email : ""}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fabric Table -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Fabric Details</h5>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Fabric Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Quantity (meters)</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Unit Price</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach($purchaseOrder->fabrics as $index => $fabric)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $fabric->fabric_name }}</td>
                                        <td>{{ $fabric->qty_in_meter }}</td>
                                        <td>Rs. {{ number_format($fabric->unit_price, 2) }}</td>
                                        <td>Rs. {{ number_format($fabric->total_price, 2) }}</td>
                                    </tr>
                                @endforeach --}}
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold">Total Fabric Price</td>
                                    {{-- <td>Rs. {{ number_format($purchaseOrder->total_fabric_price, 2) }}</td> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Table -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-uppercase text-secondary text-xxs font-weight-bolder">Products</h5>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Product</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Pieces Per Qty</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Piece Price</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($purchaseOrder->products as $index => $product)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{}}</td>
                                    <td>12</td>
                                    <td>Rs. 20.00</td>
                                    <td>Rs. 240.00</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No Products Found</td>
                                </tr>
                            @endforelse
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold">Total PO Price</td>
                                    <td>Rs. 240.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
