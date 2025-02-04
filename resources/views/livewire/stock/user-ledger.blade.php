<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="block-heading m-0">User Ledger</h4>
        {{-- <button class="btn btn-cta btn-sm">
            <i class="material-icons text-white" style="font-size: 15px;">refresh</i>
            Refresh
        </button> --}}
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label text-xs">From Date</label>
            <input type="date" class="form-control border border-2" wire:model="from_date" value="{{$from_date}}" placeholder="From" autocomplete="off">
        </div>
        <div class="col-md-3">
            <label class="form-label text-xs">To Date</label>
            <input type="date" class="form-control border border-2" wire:model="to_date" value="{{$to_date}}" placeholder="To" autocomplete="off">
        </div>
            <div class="col-md-3">
                <label class="form-label text-xs">User Type</label>
                <select class="form-control border border-2" wire:change="ChangeUsertype($event.target.value)">
                    <option selectd hidden>Choose User Type</option>
                    <option value="customer">Customer</option>
                    {{-- <option value="staff">Staff</option>
                    <option value="partner">Partner</option>
                    <option value="supplier">Supplier</option> --}}
                </select>
            </div>
            @if ($userType)
                <div class="col-md-3 mt-3">
                    <label class="form-label text-xs">Search {{ ucwords($userType) }}</label>
                    <input type="text" class="form-control border border-2"
                        wire:model="search"
                        wire:keyup="searchUsers"
                        placeholder="Search {{ ucwords($userType) }}...">
                    
                    @if (!empty($results))
                        <ul class="list-group mt-2">
                            @foreach ($results as $name)
                                <li class="list-group-item" style="cursor: pointer;" wire:click="selectUser('{{$name}}')">{{ $name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
       
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Transaction Id / Voucher No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Purpose</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Debit</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Credit</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Closing</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ledgerData as $entry)
                            <tr>
                                <td>{{ $entry->transaction_date }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ $entry->transaction_type == 'Debit' ? $entry->paid_amount : '0' }}</td>
                                <td>{{ $entry->transaction_type == 'Credit' ? $entry->paid_amount : '0' }}</td>
                                <td>{{ $entry->remaining_amount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
