<div>
    <div class="card my-4">
        <div class="card-header pb-0">
            <div class="row">
                @if(session()->has('message'))
                    <div class="alert alert-success" id="flashMessage">
                        {{ session('message') }}
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
                <a href="{{route('admin.order.new')}}" class="btn btn-primary mb-3">
                    <i class="material-icons text-white">add</i>Place New Order
                </a>
            </div>
            <div class="row">
            <!-- Orders Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Number</th>
                        <th>Customer Name</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @forelse($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->total_amount }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger" wire:click="deleteOrder({{ $order->id }})">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No orders found.</td>
                    </tr>
                    @endforelse --}}
                </tbody>
            </table>

    <!-- Pagination Links -->
    {{-- {{ $orders->links() }} --}}
</div>
