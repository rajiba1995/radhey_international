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
            <input type="date" class="form-control border border-2" placeholder="From">
        </div>
        <div class="col-md-3">
            <label class="form-label text-xs">To Date</label>
            <input type="date" class="form-control border border-2" placeholder="To">
        </div>
        <div class="col-md-3">
            <label class="form-label text-xs">User Type</label>
            <select class="form-control border border-2">
                <option selectd hidden>Choose User Type</option>
                <option>Customer</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label text-xs">Customer</label>
            <input type="text" class="form-control border border-2" placeholder="Customer">
        </div>
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
                        <tr>
                            {{-- <td>01/01/2025</td>
                            <td>001</td>
                            <td>Opening Balance</td>
                            <td>12,876</td>
                            <td>0</td>
                            <td>12,876 Cr</td> --}}
                        </tr>
                        <tr>
                            {{-- <td colspan="4" class="text-end font-weight-bold">Closing Amount</td>
                            <td colspan="2">12,876 Cr</td> --}}
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
