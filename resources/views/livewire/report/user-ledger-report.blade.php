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
                    
                    {{-- Search by Date Range --}}
                    <div class="col-auto mt-0">
                        <x-form-lable>From Date</x-form-label>
                        <input type="date"  wire:change="getUserLedger" wire:model="from_date" wire:key="from_date" class="form-control select-md bg-white" placeholder="From Date">
                    </div>
                    <div class="col-auto mt-0">
                    <x-form-lable>To Date</x-form-label>
                        <input type="date"  wire:change="getUserLedger" wire:model="to_date" wire:key="to_date" class="form-control select-md bg-white" placeholder="To Date">
                    </div>

                    {{-- User Type Dropdown --}}
                    <div class="col-auto mt-0">
                        <x-form-lable>Chose User Type</x-form-label>
                        <select wire:change="getUser" wire:model="user_type" wire:key="user_type" class="form-control select-md bg-white">
                            <option value="" hidden selected>Select User Type</option>
                            <option value="staff">Staff</option>
                            <option value="customer">Customer</option>
                            <option value="supplier">Supplier</option>
                        </select>

                    </div>

                    {{-- User Name Dropdown (Changes Based on User Type Selection) --}}
                    <div class="col-auto mt-0">
                    
                        @if($user_type === 'staff')  {{-- Staff --}}
                            <x-form-lable>Search Staff</x-form-label>
                            <select wire:change="getUserLedger" wire:model="staff_id" wire:key="staff_id" class="form-control select-md bg-white">
                                <option value="" hidden selected>Select User</option>
                                @foreach($staffs as $staff)
                                    <option value="{{ $staff->id }}">{{ ucwords($staff->name) }}</option>
                                @endforeach
                            </select>
                        @elseif($user_type === 'customer') {{-- Customer --}}
                        <x-form-lable>Search Customer</x-form-label>
                        <select  wire:change="getUserLedger" wire:model="customer_id" wire:key="customer_id" class="form-control select-md bg-white">
                            <option value="" hidden selected>Select User</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ ucwords($customer->name) }}</option>
                            @endforeach
                        </select>

                        @elseif($user_type === 'supplier') {{-- Supplier --}}
                            <x-form-lable>Search Supplier</x-form-label>
                            <select  wire:change="getUserLedger" wire:model="supplier_id" wire:key="supplier_id" class="form-control select-md bg-white">
                                <option value="" hidden selected>Select User</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ ucwords($supplier->name) }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    {{-- Payment Type Dropdown --}}
                    <div class="col-auto mt-0">
                        <x-form-lable>Bank/Cash</x-form-label>
                        <select wire:change="getUserLedger" wire:key="bank_cash"  wire:model="bank_cash" class="form-control select-md bg-white">
                            <option value="" hidden selected>Select Payment Mode</option>
                            <option value="bank">Bank</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>

                    {{-- Reset Button --}}
                    <div class="col-auto mt-5">
                        <!-- <x-form-lable></x-form-label> -->
                        <button type="button"  wire:click="resetForm" class="btn btn-outline-danger select-md">Clear</button>
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
                                    <!-- <th>#</th>
                                    <th>Payment Date</th>
                                    <th>Collected By</th>
                                    <th>Customer</th>
                                    <th>Collection Amount</th>
                                    <th>Collected From</th>
                                    <th>Status</th>
                                    <th>Entered at</th> -->

                                    <th>#</th>
                                    <th>Updated Date</th>
                                    <th>Date</th>
                                    <th>Transaction Id / Voucher No</th>
                                    <th>Purpose</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Bank/Cash</th>
                                    <th>Entered at</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentData as $index => $payment)
                                    <tr class="store_details_row cursor-pointer {{$active_details == $payment->id ? 'tr_active' : ''}}" wire:click="customerDetails({{ $payment->id }})">
                                        
                                        <td class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $payment->updated_at?date('d-m-Y', strtotime($payment->updated_at)):"" }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ date('d-m-Y', strtotime($payment->created_at)) }}</td>
                                        <td class="text-xs font-weight-bold mb-0">{{ $payment->transaction_id }}</td>
                                        <td class="text-xs font-weight-bold mb-0"><strong>{{ ucwords(str_replace('_', ' ', $payment->purpose)) }}
                                        </strong></td>
                                        <td class="text-xs font-weight-bold mb-0 text-danger">{{ $payment->is_debit==1?number_format((float)$payment->transaction_amount):"" }}</td>
                                        <td class="text-xs font-weight-bold mb-0 ">{{ $payment->is_credit==1?number_format((float)$payment->transaction_amount):"" }}</td>
                                        
                                        <td class="text-xs font-weight-bold mb-0"><span class="badge bg-success">{{ ucwords($payment->bank_cash) }}</span></td>
                                        <td class="text-xs font-weight-bold mb-0">{{ date('d/m/Y', strtotime($payment->entry_date)) }}</td>

                                        <td class="text-xs font-weight-bold mb-0">
                                            @if (!empty($payment->status==0))
                                                <span class="badge bg-success">pending</span>
                                            @else
                                                <span class="badge bg-danger">Not Approved</span>
                                            @endif
                                        </td>
                                        <td class="text-xs font-weight-bold mb-0">
                                            @if (empty($payment->is_credit))
                                                <a href="{{ route('admin.report.user_ledger', $payment->id) }}" class="btn btn-md btn-warning">Approve</a>
                                                <a href="#" onclick="return confirm('Are you sure want to remove?');" class="btn btn-outline-danger">Remove</a>
                                            @endif
                                        </td>
                                        


                                         {{-- <td>{{ date('d/m/Y', strtotime(payment->cheque_date)) }}</td>
                                        <td>{{ payment->staff->name ?? '' }}</td>
                                        <td><strong>{{ payment->customer->name ?? '' }}</strong></td>
                                        <td>Rs. {{ number_format((float)payment->transaction_amount, 2, '.', '') }} ({{ ucwords($payment->purpose) }})</td>
                                        <td><span class="badge bg-success">{{ ucwords(payment->bank_cash) }}</span></td>
                                        <td>
                                            if (!empty(payment->status==0))
                                                <span class="badge bg-success">pending</span>
                                            else
                                                <span class="badge bg-danger">Not Approved</span>
                                            endif
                                        </td>
                                        <td>
                                            if (empty(payment->is_credit))
                                                <a href="{{ route('admin.report.user_ledger', $payment->id) }}" class="btn btn-md btn-warning">Approve</a>
                                                <a href="#" onclick="return confirm('Are you sure want to remove?');" class="btn btn-outline-danger">Remove</a>
                                            endif
                                           
                                        </td>
                                        <td>{{ date('d/m/Y H:i A', strtotime(payment->created_at)) }}</td> --}}
                                    </tr>
                                    @if($active_details==$payment->id) 
                                    <tr>                        
                                        <td colspan="5" class="store_details_column">
                                            <div class="store_details">
                                                <table class="table">
                                                    <tr>
                                                    @if($payment->customer)

                                                        <td class="text-xs font-weight-bold mb-0">
                                                            <span>Customer Name: <strong>{{$payment->customer->name}} </strong> </span> 

                                                        </td>
                                                        @if (!empty($payment->customer->name))
                                                        <td class="text-xs font-weight-bold mb-0">
                                                            <span>Company Name: <strong>{{$payment->customer->company_name}} </strong> </span> 
                                                        </td> 
                                                        @endif  
                                                        @if (!empty($payment->customer->phone))
                                                            <td class="text-xs font-weight-bold mb-0">                                            
                                                                <span>Phone: <strong>{{$payment->customer->phone}} </strong> </span>  
                                                            </td>  
                                                        @endif    
                                                    @elseif($payment->staff)

                                                        <td class="text-xs font-weight-bold mb-0">
                                                            <span>Staff Name: <strong>{{$payment->staff->name}} </strong> </span> 

                                                        </td>
                                                        @if (!empty($payment->staff->name))
                                                        <td class="text-xs font-weight-bold mb-0">
                                                            <span>Company Name: <strong>{{$payment->staff->company_name}} </strong> </span> 
                                                        </td> 
                                                        @endif  
                                                        @if (!empty($payment->staff->phone))
                                                        <td class="text-xs font-weight-bold mb-0">                                            
                                                            <span>Phone: <strong>{{$payment->staff->phone}} </strong> </span>  
                                                        </td>  
                                                        @endif    
                                                        @elseif ($payment->supplier)

                                                    <td class="text-xs font-weight-bold mb-0">
                                                        <span>Supplier Name: <strong>{{$payment->supplier->name}} </strong> </span> 

                                                    </td>
                                                    @if (!empty($payment->supplier->name))
                                                    <td class="text-xs font-weight-bold mb-0">
                                                        <span>Company Name: <strong>{{$payment->supplier->company_name}} </strong> </span> 
                                                    </td> 
                                                    @endif  
                                                    @if (!empty($payment->supplier->phone))
                                                    <td class="text-xs font-weight-bold mb-0">                                            
                                                        <span>Phone: <strong>{{$payment->supplier->phone}} </strong> </span>  
                                                    </td>  
                                                    @endif    
                                                    @endif     
                                                    </tr>                                    
                                                    <tr>   
                                                        @if (!empty($payment->bank_name))
                                                        <td class="text-xs font-weight-bold mb-0"><span>Bank: <strong>{{ ($payment->bank_name)}}</strong></span></td>    
                                                        @endif
                                                        @if (!empty($payment->payment_type))
                                                        <td class="text-xs font-weight-bold mb-0"><span>Bank: <strong>{{ ucwords($payment->payment_type)}}</strong></span></td>    
                                                        @endif
                                                        @if (!empty($payment->chq_utr_no))
                                                        <td class="text-xs font-weight-bold mb-0"><span>Cheque / UTR No: <strong>{{ ucwords($payment->cheque_number)}}</strong></span></td>    
                                                        @endif
                                                        @if (!empty($payment->cheque_date))
                                                        <td class="text-xs font-weight-bold mb-0"><span>Payment Date: <strong>{{ date('d/m/Y', strtotime($payment->cheque_date))}}</strong></span></td>    
                                                        @endif
                                                        @if (!empty($payment->vouchar_no))
                                                        <td class="text-xs font-weight-bold mb-0"><span>Voucher No: <strong>{{ ($payment->vouchar_no)}}</strong></span></td>    
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
