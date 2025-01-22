

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary">Generate GRN</h5>
        </div>
        <div class="card-body">
            <h6 class="mb-4 text-muted">PO: <span class="text-dark">{{ $purchaseOrder->unique_id }}</span></h6>
            <table class="table table-bordered table-hover align-middle">
                    <thead>
                    <tr class="text-center">
                        <th>Collection</th>
                        <th>Product Name</th>
                        <th>Fabric Name</th>
                        <th>Quantity (in pieces)</th>
                        <th>Unique Number</th>
                        <th>Quantity (in meters)</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder->orderproducts as $orderProduct)
                        <tr class="text-center">
                            <td>{{ $orderProduct->collection?$orderProduct->collection->title : ""}}</td>
                            <td>{{ $orderProduct->product?$orderProduct->product->name : ""}}</td>
                            <td>{{ $orderProduct->fabric?$orderProduct->fabric->title : "" }}</td>
                            <td>{{ $orderProduct->qty_in_pieces }}</td>
                            <td>{{ $orderProduct->unique_number }}</td>
                            <td>{{ $orderProduct->qty_in_meter }}</td>
                           
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

