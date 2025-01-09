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
                <!-- <div class="d-flex align-items-center">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control border border-2 p-2 custom-input-sm" placeholder="Search order">
                    <button type="button" wire:click="$refresh" class="btn btn-dark text-light mb-0 custom-input-sm">
                        <span class="material-icons">search</span>
                    </button>
                </div> -->

                <div class="d-flex align-items-center">
                    <!-- Text Search -->
                    <label for="start_date" class="ms-2">Start Date</label>
                    <input type="date" name="start_date" wire:model="start_date" class="form-control border border-1 ms-2" id="start_date">
                    <label for="end_date" class="ms-2">End Date</label>
                    <input type="date" name="end_date" wire:model="end_date" id="end_date" class="form-control border border-1 ms-2">
                      <!-- Dropdown for Created By -->
                      <select 
                        wire:model="created_by" 
                        wire:change="$refresh" 
                        class="form-control border border-2 p-2 ms-2 custom-input-sm">
                        <option value="" selected hidden>Salesman</option>
                        @foreach($usersWithOrders  as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <input 
                        type="text" 
                        wire:model.debounce.500ms="search" 
                        class="form-control border border-2 p-2 custom-input-sm" 
                        placeholder="Search order">

                  

                    <!-- Search Button -->
                    
                    <button 
                        type="button" 
                        wire:click="$refresh" 
                        class="btn btn-dark text-light mb-0 custom-input-sm ms-2">
                        <span class="material-icons">search</span>
                    </button>
                    <a href=""  class="btn btn-dark text-light mb-0 custom-input-sm ms-2">
                        <span class="material-icons">refresh</span>
                   </a>
                </div>

                <a href="{{route('admin.order.new')}}" class="btn btn-primary mb-3 btn-sm">
                    <i class="material-icons text-white" style="font-size: 15px;">add</i>Place New Order
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
                               <a href="{{route('admin.order.view',$order->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm" data-toggle="tooltip" data-original-title="View product">
                                    <span class="material-icons">visibility</span>
                                </a>

                                <a href="{{route('admin.order.edit', $order->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm" data-toggle="tooltip" data-original-title="Edit product">
                                    <span class="material-icons">edit</span>
                                </a>
                                <a href="{{route('admin.order.ledger.view', $order->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm">Ledger History</a>
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
