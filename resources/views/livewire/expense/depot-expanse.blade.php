<div>
    <!-- Nav Tabs -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#dailyCollection">Daily Collection</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#dailyExpenses">Daily Expenses</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3">
        <!-- Daily Expenses Tab -->
        <div class="tab-pane fade" id="dailyExpenses">
            <div class="row mb-4">
                <!-- Filters -->
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" wire:model="filter_date" class="form-control" placeholder="Select Date">
                        </div>
                        <div class="col-md-4">
                            <input type="text" wire:model="filter_name" class="form-control" placeholder="Search by Name">
                        </div>
                        <div class="col-md-4 text-end">
                            <a type="button" class="btn btn-primary btn-sm" href="{{route('depot-expense.daily.expenses')}}">
                                + Add Expense
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Expenses List -->
                <div class="col-lg-12 col-md-6">
                    <div class="card my-4">
                        <div class="card-header pb-0">
                            <h6>Daily Expenses</h6>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            <th>Date</th>
                                            <th>Transaction Type</th>
                                            <th>Paid Amount</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">Jan 01, 2025</td>
                                            <td class="text-center">Cash</td>
                                            <td class="text-center">$100.00</td>
                                            <td class="text-center">Office supplies</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Jan 02, 2025</td>
                                            <td class="text-center">Online</td>
                                            <td class="text-center">$50.00</td>
                                            <td class="text-center">Internet Bill</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-center">No transactions found.</td>
                                        </tr>
                                        <tr class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">
                                            <td colspan="2">Total Amount: $150.00</td>
                                            <td>Total Paid Amount: $150.00</td>
                                            <td class="text-success">Total Remaining Amount: $0.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Expense Form (Modal) -->
                <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Expense</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form wire:submit.prevent="addExpense">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <select class="form-control" wire:model="payment_method">
                                            <option value="" hidden>Select...</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Online">Online</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Paid Amount</label>
                                        <input type="number" wire:model="paid_amount" class="form-control" required>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Remarks</label>
                                        <textarea wire:model="remarks" class="form-control"></textarea>
                                    </div>
                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Add Expense
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Daily Collection Tab -->
        <div class="tab-pane fade show active" id="dailyCollection">
            <div class="card my-4">
                <div class="card-header pb-0">
                    <h6>Add Payment</h6>
                </div>
                <div class="card-body px-0 pb-2">
                    <form wire:submit.prevent="addPayment">
                        <div class="row p-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <select class="form-control" wire:model="payment_method">
                                        <option value="" hidden>Select...</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Online">Online</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Paid Amount</label>
                                    <input type="number" wire:model="paid_amount" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea wire:model="remarks" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 text-end mt-3">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Add Payment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
