
    <!-- Navbar -->
    <!-- End Navbar -->
    <!-- <div class="container-fluid py-4"> -->
    <div class="container">
        <section class="admin__title">
            <h5>Expenses List</h5>
        </section>
        <section>
            <div class="search__filter">
                <div class="row align-items-center justify-content-end">
                    <div class="col-auto">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-auto mt-3">
                                <a href="{{ route('admin.accounting.add_depot_expense') }}" class="btn btn-outline-success select-md">Add Expense</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                        <!-- <p class="text-sm font-weight-bold">Items</p> -->
                    </div>
                    <div class="col-auto">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto mt-0">
                                <!-- <label for="" class="date_lable">Payment Date</label> -->
                                <input type="date" wire:model="paymentDate" wire:change="AddPaymentDate($event.target.value)"
                                    class="form-control select-md bg-white">
                            </div>
                            <div class="col-auto mt-0">
                                <input type="text" wire:model="search" class="form-control select-md bg-white" id="customer"
                                    placeholder="Search Expense by Transaction ID or Amount" value=""
                                    style="width: 250px;"  wire:keyup="FindExpense($event.target.value)">
                            </div>
                    
                            <div class="col-auto mt-3">
                                <button type="button" wire:click="resetForm" class="btn btn-outline-danger select-md">Clear</button>
                            </div>
                            <div class="col-auto">
                                <button wire:click="export" class="btn btn-outline-success select-md"><i class="fas fa-file-csv me-1"></i>Export</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="card my-2">
            <div class="row">
                <div class="col-md-12">           
                    <div class="table-responsive"> 
                        <table class="table table-sm table-hover ledger">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Expense Date</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = empty(Request::get('page')) || Request::get('page') == 1 ? 1 : (((Request::get('page')-1)*$paginate)+1);
                                @endphp
                                @forelse ($expenses as $key=>$item)
                                @php
                                    $ExpenseAt = "";
                                    $ExpenseType = "";

                                    $expenseData =($item->staff_id ? DB::table('users')->where('id', $item->staff_id)->first() :
                                                ($item->customer_id ? DB::table('users')->where('id', $item->customer_id)->first() :
                                                ($item->supplier_id ? DB::table('suppliers')->where('id', $item->supplier_id)->first() : null)));

                                
                                    
                                    $expenseType = $item->expense_id ? DB::table('expences')->where('id', $item->expense_id)->first() : null;
                                    $ExpenseType = $expenseType ? $expenseType->title : "";
                                @endphp
                                <tr class="store_details_row">  
                                    <td>{{$i}}</td>
                                    <td>@if($item->payment_date){{date('d/m/Y', strtotime($item->payment_date))}}@endif</td>    
                                    <td>{{ $item->voucher_no }}</td>
                                    <td>Rs. {{number_format((float)$item->amount, 2, '.', '')}} ( {{ucwords($item->bank_cash)}} )</td>    
                                    <td>
                                        <a href="{{ route('admin.accounting.edit_depot_expense', $item->id) }}" class="btn btn-outline-success select-md">Edit</a>
                                    </td>                                   
                                </tr>
                                @php $i++; @endphp
                                @empty
                                <tr>
                                    <td colspan="5">No records found</td>
                                </tr>    
                                @endforelse
                            </tbody>
                        </table>   
                    </div>
                    {{$expenses->links()}}
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('close-import-modal', event => {
            var importModal = document.getElementById('importModal');
            var modal = bootstrap.Modal.getInstance(importModal);
            modal.hide();
        });
    </script>
    