<div class="container">
    <section class="admin__title">
        <h5>User Ledger</h5>
    </section>

    <section>
        <ul class="breadcrumb_menu">
            <li>Report</li>
            <li><a href="{{route('admin.report.user_ledger')}}">User Ledger</a></li>
            <li class="back-button"></li>
        </ul>
    </section>

    <div class="search__filter">
        <div class="row align-items-center justify-content-end">
            <div class="col-auto">
                <div class="row g-3 align-items-center">
                    <div class="col-auto mt-0">
                        <input type="text" wire:model="selected_customer" class="form-control select-md bg-white"
                            placeholder="Search customer by name or order number"
                            wire:keyup="FindCustomer($event.target.value)" style="width: 350px;">
                        @if(!empty($searchResults))
                            <div id="fetch_customer_details" class="dropdown-menu show" style="max-height: 200px; width: 350px; overflow-y: auto;">
                                @foreach ($searchResults as $customer_item)
                                    <button class="dropdown-item" type="button" wire:click="selectCustomer({{ $customer_item->id }})">
                                        <img src="{{ asset($customer_item->profile_image ?: 'assets/img/user.png') }}" alt=""> 
                                        {{ $customer_item->name }} ({{ $customer_item->phone }})
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="col-auto mt-0">
                        <select wire:model="staff_id" class="form-control select-md bg-white">
                            <option value="" hidden selected>Collected By</option>
                            @foreach($staffs as $staff)
                                <option value="{{$staff->id}}">{{ ucwords($staff->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto mt-3">
                        <button type="button" wire:click="resetForm" class="btn btn-outline-danger select-md">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter">
        <div class="row align-items-center justify-content-end">
            <div class="col-auto">
                <p class="text-sm font-weight-bold">{{$paymentData->total()}} Items</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover ledger">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Payment Date</th>
                                    <th>Collected By</th>
                                    <th>Customer</th>
                                    <th>Collection Amount</th>
                                    <th>Collected From</th>
                                    <th>Status</th>
                                    <th>Entered at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentData as $index => $payment)
                                    <tr class="store_details_row cursor-pointer {{$active_details == $payment->id ? 'tr_active' : ''}}" wire:click="customerDetails({{ $payment->id }})">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ date('d/m/Y', strtotime($payment->cheque_date)) }}</td>
                                        <td>{{ $payment->staff->name ?? '' }}</td>
                                        <td><strong>{{ $payment->customer->name ?? '' }}</strong></td>
                                        <td>Rs. {{ number_format((float)$payment->transaction_amount, 2, '.', '') }} ({{ ucwords($payment->purpose) }})</td>
                                        <td><span class="badge bg-success">{{ ucwords($payment->bank_cash) }}</span></td>
                                        <td>
                                            @if (!empty($payment->status==0))
                                                <span class="badge bg-success">pending</span>
                                            @else
                                                <span class="badge bg-danger">Not Approved</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (empty($payment->is_credit))
                                                <a href="{{ route('admin.report.user_ledger', $payment->id) }}" class="btn btn-md btn-warning">Approve</a>
                                                <a href="#" onclick="return confirm('Are you sure want to remove?');" class="btn btn-outline-danger">Remove</a>
                                            @endif
                                           
                                        </td>
                                        <td>{{ date('d/m/Y H:i A', strtotime($payment->created_at)) }}</td>
                                    </tr>
                                    @if($active_details==$payment->id) 
                                    <tr>                        
                                        <td colspan="5" class="store_details_column">
                                            <div class="store_details">
                                                <table class="table">
                                                    <tr>
                                                        <td>
                                                            <span>Customer Name: <strong>{{$payment->customer->name}} </strong> </span> 
                                                        </td>
                                                        @if (!empty($payment->customer->name))
                                                        <td>
                                                            <span>Company Name: <strong>{{$payment->customer->company_name}} </strong> </span> 
                                                        </td> 
                                                        @endif  
                                                        @if (!empty($payment->customer->phone))
                                                        <td>                                            
                                                            <span>Phone: <strong>{{$payment->customer->phone}} </strong> </span>  
                                                        </td>  
                                                        @endif    
                                                    </tr>                                    
                                                    <tr>   
                                                        @if (!empty($payment->bank_name))
                                                        <td><span>Bank: <strong>{{ ($payment->bank_name)}}</strong></span></td>    
                                                        @endif
                                                        @if (!empty($payment->payment_type))
                                                        <td><span>Bank: <strong>{{ ucwords($payment->payment_type)}}</strong></span></td>    
                                                        @endif
                                                        @if (!empty($payment->chq_utr_no))
                                                        <td><span>Cheque / UTR No: <strong>{{ ucwords($payment->cheque_number)}}</strong></span></td>    
                                                        @endif
                                                        @if (!empty($payment->cheque_date))
                                                        <td><span>Payment Date: <strong>{{ date('d/m/Y', strtotime($payment->cheque_date))}}</strong></span></td>    
                                                        @endif
                                                        @if (!empty($payment->vouchar_no))
                                                        <td><span>Voucher No: <strong>{{ ($payment->vouchar_no)}}</strong></span></td>    
                                                        @endif
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>  
                                    @endif  
                                @empty
                                    <tr>
                                        <td colspan="9" class="border px-4 py-2 text-center">No data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $paymentData->links() }} 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="loader-container" wire:loading>
        <div class="loader"></div>
    </div>
</div>
