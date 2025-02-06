<div class="container-fluid px-2 px-md-4">
    <div class="card card-body">
        <h4 class="m-0">Add Daily Expense</h4>
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                        <h6 class="badge bg-danger custom_danger_badge">Expense Information</h6>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="#" class="btn btn-cta">
                            <i class="material-icons text-white" style="font-size: 15px;">chevron_left</i> Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-3">
                <form action="path-to-your-submit-endpoint" method="POST" enctype="multipart/form-data">
                    <!-- Basic Expense Information Section -->
                    <div class="col-md-8 mb-2 d-flex align-items-center">
                        <h6 class="badge bg-danger custom_danger_badge">Expense Details</h6>
                    </div>
                    <div class="row mb-3">
                        <!-- <div class="mb-3 col-md-4">
                            <label for="user_id" class="form-label">User ID <span class="text-danger">*</span></label>
                            <input type="number" name="user_id" id="user_id" class="form-control form-control-sm" placeholder="Enter User ID" required>
                        </div> -->

                        <div class="mb-3 col-md-4">
                            <label for="admin_id" class="form-label">Expense At</label>
                            <select name="admin_id" id="admin_id" class="form-control form-control-sm">
                                <option value="" disabled selected>Select One</option>
                                <!-- You can dynamically populate the options here using PHP or other methods -->
                                <option value="1">Stuff</option>
                                <option value="2">Supplier</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>


                        <div class="mb-3 col-md-4">
                            <label for="admin_id" class="form-label">Stuff Name</label>
                            <select name="admin_id" id="admin_id" class="form-control form-control-sm">
                                <option value="" disabled selected>Select One</option>
                                <!-- You can dynamically populate the options here using PHP or other methods -->
                                <option value="1">Papiya</option>
                                <option value="2">Sujit</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="admin_id" class="form-label">Expense For</label>
                            <select name="admin_id" id="admin_id" class="form-control form-control-sm">
                                <option value="" disabled selected>Select One</option>
                                <!-- You can dynamically populate the options here using PHP or other methods -->
                                <option value="1">Buy Anything</option>
                                <option value="2">Supplier</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="expense_id" class="form-label">Expense ID <span class="text-danger">*</span></label>
                            <input type="number" name="expense_id" id="expense_id" class="form-control form-control-sm" placeholder="Enter Expense ID" required>
                        </div>

                        <!-- <div class="mb-3 col-md-4">
                            <label for="payment_for" class="form-label">Payment For</label>
                            <input type="text" name="payment_for" id="payment_for" class="form-control form-control-sm" placeholder="Enter Payment Purpose">
                        </div> -->

                        <div class="mb-3 col-md-4">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control form-control-sm" placeholder="Enter Amount">
                        </div>
                    </div>

                    <!-- Payment Details Section -->
                    <div class="col-md-8 mb-2 d-flex align-items-center">
                        <h6 class="badge bg-danger custom_danger_badge">Payment Information</h6>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-3 col-md-4">
                            <label for="payment_in" class="form-label">Payment In</label>
                            <select name="payment_in" id="payment_in" class="form-control form-control-sm">
                                <option value="" disabled selected>Select One</option>
                                <!-- You can dynamically populate the options here using PHP or other methods -->
                                <option value="1">Online</option>
                                <option value="2">Cash</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                        <!-- <div class="mb-3 col-md-4">
                            <label for="bank_cash" class="form-label">Bank/Cash</label>
                            <input type="text" name="bank_cash" id="bank_cash" class="form-control form-control-sm" placeholder="Enter Bank or Cash">
                        </div> -->

                        <!-- <div class="mb-3 col-md-4">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control form-control-sm">
                        </div> -->

                        <div class="mb-3 col-md-4">
                            <label for="payment_mode" class="form-label">Payment Mode</label>
                            <select name="payment_mode" id="payment_mode" class="form-control form-control-sm" >
                                <option value="" disabled selected>Select One</option>
                                <!-- You can dynamically populate the options here using PHP or other methods -->
                                <option value="1">Online</option>
                                <option value="2">Cash</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="voucher_no" class="form-label">Voucher No</label>
                            <input type="text" name="voucher_no" id="voucher_no" class="form-control form-control-sm" placeholder="Enter Voucher Number">
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="col-md-8 mb-2 d-flex align-items-center">
                        <h6 class="badge bg-danger custom_danger_badge">Additional Information</h6>
                    </div>
                    <div class="row mb-3">
                        <!-- <div class="mb-3 col-md-4">
                            <label for="chq_utr_no" class="form-label">Cheque/UTR No</label>
                            <input type="text" name="chq_utr_no" id="chq_utr_no" class="form-control form-control-sm" placeholder="Enter Cheque/UTR No">
                        </div> -->

                        <!-- <div class="mb-3 col-md-4">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control form-control-sm" placeholder="Enter Bank Name">
                        </div> -->

                        <div class="mb-3 col-md-4">
                            <label for="narration" class="form-label">Narration</label>
                            <textarea name="narration" id="narration" class="form-control form-control-sm" placeholder="Enter Narration"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-cta mt-3">Save Expense</button>
                </form>
            </div>
        </div>
    </div>
</div>
