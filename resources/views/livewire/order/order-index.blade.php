<div>
    <div class="card my-4">
        <div class="card-header pb-0">
            <div class="row">
                @if(session()->has('message'))
                    <div class="alert alert-success" id="flashMessage">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
            <div class="d-flex justify-content-between mb-3">
                <!-- Search Box -->
                <input type="text" class="form-control w-25" placeholder="Search Orders..." wire:model.debounce.300ms="search">
                <!-- Status Filter -->
                {{-- <select class="form-select w-25" wire:model="status">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select> --}}
                <a href="{{route('admin.order.new')}}" class="btn btn-primary mb-3 btn-sm">
                    <i class="material-icons text-white" style="font-size: 15px;">add</i>Generate New Order
                </a>
            </div>
            <div class="row">
            <!-- Orders Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer Name</th>
                        {{-- <th>Billing Address</th>
                        <th>Shipping Address</th> --}}
                        <th>Billing Amount</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            {{-- <td>{{ $order->billing_address }}</td>
                            <td>{{ $order->shipping_address }}</td> --}}
                            <td>{{ $order->total_amount }}</td>
                            <td>
                                <a href="#" class="badge bg-info btn-sm">{{$order->status==1?"Pending":""}}</a>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <!-- Add actions like View, Edit, or Delete -->
                                {{-- <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

   <!-- Pagination -->
   <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
