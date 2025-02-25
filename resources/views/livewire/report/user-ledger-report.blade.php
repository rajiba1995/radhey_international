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
    <section>
        <div class="search__filter">
            <div class="row align-items-center justify-content-end">
                <div class="col-auto">
                    <div class="row g-3 align-items-center">
                        {{-- Search by Date Range --}}
                        <div class="col-auto mt-0">
                            <label class="form-label"><strong>From Date</strong></label>
                            <input type="date"  wire:change="getUserLedger" wire:model="from_date" wire:key="from_date" class="form-control select-md bg-white" placeholder="From Date">
                        </div>
                        <div class="col-auto mt-0">
                        <label class="form-label"><strong>To Date</strong></label>
                            <input type="date"  wire:change="getUserLedger" wire:model="to_date" wire:key="to_date" class="form-control select-md bg-white" placeholder="To Date">
                        </div>

                        {{-- User Type Dropdown --}}
                        <div class="col-auto mt-0">
                            <label class="form-label"><strong>User Type</strong></label>
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
                                <label class="form-label"><strong>Search Staff</strong></label>
                                <input type="text" wire:model.defer="staffSearchTerm"
                                    wire:keyup="searchStaff" class="form-control form-control-sm bg-white"
                                    placeholder="Search by staff name" id="searchStaff">
                                @if(!empty($staffSearchResults))
                                <div class="dropdown-menu show w-100" style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($staffSearchResults as $staff)
                                    <button class="dropdown-item" type="button" 
                                        wire:click="selectStaff({{ $staff->id }})">
                                        {{ ucwords($staff->name) }}
                                    </button>
                                    @endforeach
                                </div>
                                @endif

                            @elseif($user_type === 'customer') {{-- Customer --}}
                                <label class="form-label"><strong>Customer</strong></label>
                                <input type="text" wire:model.defer="customerSearchTerm"
                                    wire:keyup="searchCustomer" class="form-control form-control-sm bg-white"
                                    placeholder="Search by customer name" id="searchCustomer">
                                @if(!empty($customerSearchResults))
                                    <div class="dropdown-menu show w-100" style="max-height: 200px; overflow-y: auto;">
                                        @foreach ($customerSearchResults as $customer)
                                        <button class="dropdown-item" type="button" 
                                            wire:click="selectCustomers({{ $customer->id }})">
                                            {{ ucwords($customer->name) }}
                                        </button>
                                        @endforeach
                                    </div>
                                @endif
                            @elseif($user_type === 'supplier') {{-- Supplier --}}
                                <label class="form-label"><strong>Search Supplier</strong></label>
                                <input type="text" wire:model.defer="supplierSearchTerm"
                                    wire:keyup="searchSupplier" class="form-control form-control-sm"
                                    placeholder="Search by supplier name" id="searchSupplier">
                                @if(!empty($supplierSearchResults))
                                    <div class="dropdown-menu show w-100" style="max-height: 200px; overflow-y: auto;">
                                        @foreach ($supplierSearchResults as $supplier)
                                            <button class="dropdown-item" type="button" 
                                                wire:click="selectSupplier({{ $supplier->id }})">
                                                {{ ucwords($supplier->name) }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Payment Type Dropdown --}}
                        <div class="col-auto mt-0">
                            <label class="form-label"><strong>Bank/Cash</strong></label>
                            <select wire:change="getUserLedger" wire:key="bank_cash"  wire:model="bank_cash" class="form-control select-md bg-white">
                                <option value="" hidden selected> Mode</option>
                                <option value="bank">Bank</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>

                        {{-- Reset Button --}}
                        <div class="col-auto mt-5">
                            <!-- <label class="form-label"><strong></strong></label> -->
                            <button type="button"  wire:click="resetForm" class="btn btn-outline-danger select-md">Clear</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    <section>
        
    </section>
    @if($showList && count($ledgerData) > 0)
    <div class="filter">
        <div class="row align-items-center justify-content-end">
            <div class="col-auto">
                <div class="col-auto">
                <button wire:click="exportLedger" class="btn btn-outline-success select-md">
                    <i class="fas fa-file-export"></i>Download CSV
                </button>
                <button wire:click="generatePDF" class="btn btn-outline-success select-md">
                    <i class="fas fa-file-pdf"> </i> Download Pdf
                </button>
                </div>

                <!-- <div class="col-auto">
                    <button wire:click="generatePDF" class="btn btn-outline-primary select-md btn_outline">Download Invoice</button>
                </div> -->


               
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                    </div>
                </div>
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
                                    <th>Updated Date</th>
                                    <th>Date</th>
                                    <th>Transaction Id / Voucher No</th>
                                    <th>Purpose</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Closing</th>
                                    <th>Entered at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ledgerData as $index => $payment)
                                    <tr  class="store_details_row cursor-pointer {{$active_details==$payment->id?"tr_active":""}}" wire:click="customerDetails({{$payment->id}})">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $payment->updated_at?date('d-m-Y', strtotime($payment->updated_at)):"" }}</td>
                                        <td>{{ date('d-m-Y', strtotime($payment->created_at)) }}</td>
                                        <td>{{ $payment->transaction_id }}</td>
                                        <td>
                                            <strong>{{ ucwords(str_replace('_', ' ', $payment->purpose)) }}({{ ucwords($payment->bank_cash) }})</strong>
                                        </td>
                                        <td class="text-danger">{{ $payment->is_debit == 1?number_format((float)$payment->transaction_amount):"" }}</td>
                                        <td class="text-success">{{ $payment->is_credit == 1?number_format((float)$payment->transaction_amount):"" }}</td>
                                        <td></td>
                                        <td>{{ date('d-m-Y', strtotime($payment->entry_date)) }}</td>
                                        <td>
                                            @if($payment->is_credit == 1)
                                            <a href="#" class="btn btn-outline-success select-md btn_outline">Edit Payment</a>
                                            @endif
                                            {{-- @if ($payment->status == 0)
                                                <span class="badge bg-success">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Not Approved</span>
                                            @endif --}}
                                        </td>
                                    </tr>

                                    @if($active_details == $payment->id)
                                        <tr>                        
                                            <td colspan="5" class="store_details_column">
                                                <div class="store_details">
                                                    <table class="table">
                                                        <tr>
                                                        @if($payment->customer)

                                                            <td>
                                                                <span>Customer: <strong>{{$payment->customer->name}} </strong> </span> 

                                                            </td>
                                                            @if (!empty($payment->customer->name))
                                                            <td>
                                                                <span>Company: <strong>{{$payment->customer->company_name}} </strong> </span> 
                                                            </td> 
                                                            @endif  
                                                            @if (!empty($payment->customer->phone))
                                                            <td>                                            
                                                                <span>Phone: <strong>{{$payment->customer->phone}} </strong> </span>  
                                                            </td>  
                                                            @endif    
                                                        @elseif($payment->staff)
                                                            <td>
                                                                <span>Staff Name: <strong>{{$payment->staff->name}} </strong> </span> 

                                                            </td>
                                                            @if (!empty($payment->staff->name))
                                                            <td>
                                                                <span>Company: <strong>{{$payment->staff->company_name}} </strong> </span> 
                                                            </td> 
                                                            @endif  
                                                            @if (!empty($payment->staff->phone))
                                                            <td>                                            
                                                                <span>Phone: <strong>{{$payment->staff->phone}} </strong> </span>  
                                                            </td>  
                                                            @endif    
                                                            @elseif ($payment->supplier)

                                                        <td>
                                                            <span>Supplier Name: <strong>{{$payment->supplier->name}} </strong> </span> 

                                                        </td>
                                                        @if (!empty($payment->supplier->name))
                                                        <td>
                                                            <span>Company: <strong>{{$payment->supplier->company_name}} </strong> </span> 
                                                        </td> 
                                                        @endif  
                                                        @if (!empty($payment->supplier->phone))
                                                        <td>                                            
                                                            <span>Phone: <strong>{{$payment->supplier->phone}} </strong> </span>  
                                                        </td>  
                                                        @endif    
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
                        
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
    </div>
</div>
