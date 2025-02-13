<div class="container">
    <section class="admin__title">
        <h5>Add Payment Receipt</h5>
    </section>
    <section>
        <ul class="breadcrumb_menu">
            <li><a href="{{route('admin.accounting.payment_collection')}}">Payment Collection</a></li>
            <li>Add Payment Receipt</li>
            <li class="back-button">
              <a class="btn btn-dark btn-sm text-decoration-none text-light font-weight-bold mb-0" href="{{route('admin.accounting.payment_collection')}}" role="button">
                <i class="material-icons text-white" style="font-size: 15px;">chevron_left</i>
                <span class="ms-1">Back</span>
              </a>
            </li>
          </ul>
    </section>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form wire:submit.prevent="submitForm">
                        @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="" id="">Customer <span class="text-danger">*</span></label>

                                    <div class="position-relative">
                                        <input type="text" wire:keyup="FindCustomer($event.target.value)" 
                                            wire:model="customer" 
                                            class="form-control form-control-sm border border-1 customer_input" 
                                            placeholder="Search customer by name, mobile, order ID" {{$readonly}}>
                                            <input type="hidden" wire:model="customer_id" value="">
                                            @if(isset($errorMessage['customer_id']))
                                                <div class="text-danger">{{ $errorMessage['customer_id'] }}</div>
                                            @endif
                                            @if(!empty($searchResults))
                                                <div id="fetch_customer_details" class="dropdown-menu show w-100" style="max-height: 200px; overflow-y: auto;">
                                                    @foreach ($searchResults as $customer)
                                                        <button class="dropdown-item" type="button" wire:click="selectCustomer({{ $customer->id }})">
                                                            <img src="{{ $customer->profile_image ? asset($customer->profile_image) : asset('assets/img/user.png') }}" alt=""> {{ $customer->name }}  ({{ $customer->phone }}) 
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="" id="">Collected By <span class="text-danger">*</span></label>
                                    <select wire:model="staff_id" class="form-control" {{$readonly}}>
                                        <option value="">Choose an user</option>
                                        @foreach($staffs as $staff)
                                            <option value="{{$staff->id}}" {{$staff_id!=$staff->id?"hidden":""}}>{{ucwords($staff->name)}}</option>
                                        @endforeach
                                    </select>
                                    @if(isset($errorMessage['staff_id']))
                                        <div class="text-danger">{{ $errorMessage['staff_id'] }}</div>
                                    @endif
                                </div>
                            </div>
    
    
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="">Amount <span class="text-danger">*</span></label>
                                    <input type="text" value="" maxlength="20" wire:model="amount" oninput="validateNumber(this)" class="form-control" {{$readonly}}>
                                    @if(isset($errorMessage['amount']))
                                        <div class="text-danger">{{ $errorMessage['amount'] }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="">Voucher No</label>
                                    <input type="text" wire:model="voucher_no"
                                        class="form-control" disabled {{$readonly}}>
                                        @if(isset($errorMessage['voucher_no']))
                                            <div class="text-danger">{{ $errorMessage['voucher_no'] }}</div>
                                        @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="">Date <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="payment_date" id="payment_date" max="{{date('Y-m-d')}}"
                                        class="form-control" value="" {{$readonly}}>
                                        @if(isset($errorMessage['payment_date']))
                                            <div class="text-danger">{{ $errorMessage['payment_date'] }}</div>
                                        @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
    
                                <div class="form-group mb-3">
                                    <label for="">Mode of Payment <span class="text-danger">*</span></label>
                                    <select wire:model="payment_mode" class="form-control" id="payment_mode" wire:change="ChangePaymentMode($event.target.value)" {{$readonly}}>
                                        <option value="" selected="" hidden="">Select One</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="neft">NEFT</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                    @if(isset($errorMessage['payment_mode']))
                                        <div class="text-danger">{{ $errorMessage['payment_mode'] }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($activePayementMode!=="cash")
                            <div class="row" id="noncash_sec">
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                        <label for="">Cheque No / UTR No </label>
                                        <input type="text" value="" wire:model="chq_utr_no" class="form-control"
                                            maxlength="100" {{$readonly}}>
                                            @if(isset($errorMessage['chq_utr_no']))
                                                <div class="text-danger">{{ $errorMessage['chq_utr_no'] }}</div>
                                            @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                        <label for="">Bank Name </label>
                                        <div id="bank_search">
                                            <input type="text" id="" placeholder="Search Bank" wire:model="bank_name"
                                                value=""
                                                class="form-control bank_name" maxlength="200" {{$readonly}}>
                                                @if(isset($errorMessage['bank_name']))
                                                    <div class="text-danger">{{ $errorMessage['bank_name'] }}</div>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
    
                        <div class="row">
                            <div class="form-group text-end">
                                <button type="submit" id="submit_btn"
                                    class="btn btn-sm btn-success"><i class="material-icons text-white" style="font-size: 15px;">add</i>Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
      </div>
</div>
@push('js')
    <script>
        function validateNumber(input) {
        // Remove any characters that are not digits or a single decimal point
        input.value = input.value.replace(/[^0-9.]/g, '');
        
        // Ensure only one decimal point is allowed
        const parts = input.value.split('.');
        if (parts.length > 2) {
        input.value = parts[0] + '.' + parts[1];
        }
    }
</script>
@endpush
