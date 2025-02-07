<div class="container-fluid px-2 px-md-4">
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
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="d-flex justify-content-between mb-3">
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
                <button wire:click="export" class="btn btn-sm btn-success me-2">
                    <i class="fas fa-file-export"></i> Export
                </button>
                <a href="{{route('admin.order.new')}}" class="btn btn-cta">
                    <i class="material-icons text-white" style="font-size: 15px;">add</i>Place New Order
                </a>
            </div>
            <div class="table-responsive p-0">
            <!-- Orders Table -->
            
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Order #</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Customer Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Billing Amount</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Remaining Amount</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="align-center">
                                    <span class="text-dark text-sm font-weight-bold mb-0">{{ env('ORDER_PREFIX'). $order->order_number }}</span>
                                    <span class="badge" style="background:#7b809a;color:#fff;"> {{ $order->created_at->format('Y-m-d H:i') }}</span>
                                </td>
                                <td><p class="text-xs font-weight-bold mb-0">{{ $order->customer_name }}</p></td>
                                {{-- <td>{{ $order->billing_address }}</td>
                                <td>{{ $order->shipping_address }}</td> --}}
                                <td><p class="text-xs font-weight-bold mb-0">{{ $order->total_amount }}</p></td>
                                <td class="{{$order->remaining_amount>0?"text-danger":""}}"><p class="text-xs font-weight-bold mb-0">{{ $order->remaining_amount }}</p></td>
                                <td>
                                    <select name="status" class="form-select" wire:change="updateStatus($event.target.value, {{$order->id}})">
                                        @foreach (\App\Models\Order::$statuses as $key => $value)
                                            <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    {{-- <button class="badge bg-primary btn-sm">{{$order->status}}</button> --}}
                                </td>
                                <td>
                                     <a href="{{route('admin.order.view',$order->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm mb-0" data-toggle="tooltip" data-original-title="View product">
                                         <span class="material-icons">visibility</span>
                                    </a>
                                    @if($order->status=="Pending")
                                    <a href="{{route('admin.order.edit', $order->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm mb-0" data-toggle="tooltip" data-original-title="Edit product">
                                        <span class="material-icons">edit</span>
                                    </a>
                                    @endif
                                      <a href="{{route('admin.order.ledger.view', $order->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm mb-0">Ledger History</a>
                                    <a href="{{route('admin.order.invoice', $order->id)}}" target="_blank" class="btn btn-outline-info btn-sm custom-btn-sm mb-0">Invoice</a>
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
        </div>
    </div>
</div>
