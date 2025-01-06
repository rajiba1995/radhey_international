<div>
    <div class="card my-4">
        <div class="card-header pb-0">
            <div class="row">
               
                @if (session('message'))
                    <div class="alert alert-success">
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
                        <th>Billing Amount</th>
                        <th>Remaining Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <span class="badge bg-danger custom_danger_badge"> {{ $order->created_at->format('Y-m-d H:i') }}</span>
                                <br>
                                {{ $order->order_number }}
                            </td>
                            <td>{{ $order->customer_name }}</td>
                            {{-- <td>{{ $order->billing_address }}</td>
                            <td>{{ $order->shipping_address }}</td> --}}
                            <td>{{ $order->total_amount }}</td>
                            <td class="{{$order->remaining_amount>0?"text-danger":""}}">{{ $order->remaining_amount }}</td>
                            <td>
                                <a href="#" class="badge bg-info btn-sm">{{$order->status==1?"Pending":""}}</a>
                            </td>
                            <td>
                                <!-- <a href="#" class="btn btn-outline-info btn-sm custom-btn-sm" data-toggle="tooltip" data-original-title="Edit product">
                                    <span class="material-icons">edit</span>
                                </a> -->

                                <a href="{{route('admin.order.edit', $order->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm" data-toggle="tooltip" data-original-title="Edit product">
                                    <span class="material-icons">edit</span>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm custom-btn-sm">Payment History</a>
                                <a href="{{route('admin.order.invoice', $order->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm">Invoice</a>
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
