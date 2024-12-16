<div class="container mt-2">
    <div class="row">
        <!-- Staff Details and Banking Details -->
        <div class="col-lg-8">
            <div class="card p-4">
                <div class="row">
                    <div class="col-md-3 text-center">
                    @if($staff && $staff->image)
                       <img src="{{asset('storage/'.$staff->image)}}" alt="Profile Image" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    @endif
                    </div>
                    <div class="col-md-9">
                        <h4>{{$staff->name ?? ''}}</h4>
                        <p><strong>Designation:</strong> {{ ucwords($staff->designationDetails->name ?? 'N/A') }}</p>
                        <p><strong>Mobile:</strong> {{$staff->phone ?? ''}}</p>
                        <p><strong>WhatsApp:</strong> {{$staff->whatsapp_no ?? ''}}</p>
                        <p><strong>Address:</strong>{{$staff->address->address ?? ''}}</p>
                        <ul class="list-unstyled">
                            <li>Landmark: {{ $staff->address->landmark ?? 'N/A' }}</li>
                            <li>City: {{ $staff->address->city ?? 'N/A' }}</li>
                            <li>State: {{ $staff->address->state ?? 'N/A' }}</li>
                            <li>Pincode: {{ $staff->address->zip_code ?? 'N/A' }}</li>
                            <li>Aadhar Number: {{ $staff->aadhar_name ?? 'N/A' }}</li>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Banking Details</h5>
                        <ul class="list-unstyled">
                            <li><strong>A/C Holder Name:  </strong>{{ ucwords($staff->bank->account_holder_name ?? 'N/A') }}</li>
                            <li><strong>Bank Name: </strong>{{ $staff->bank->bank_name ?? 'N/A' }}</li>
                            <li><strong>Branch Name: </strong>{{ $staff->bank->branch_name ?? 'N/A' }}</li>
                            <li><strong>A/C No: </strong>{{ $staff->bank->bank_account_no ?? 'N/A' }}</li>
                            <li><strong>IFSC: </strong>{{ $staff->bank->ifsc ?? 'N/A' }}</li>
                        </ul></div>
                    <div class="col-md-6">
                        <h5>Salary & Allowances</h5>
                        <ul class="list-unstyled">
                            <li><strong>Monthly Salary:</strong> Rs. {{ $staff->bank->monthly_salary ?? '0' }}</li>
                            <li><strong>Daily Salary:</strong> Rs. {{$staff->bank->daily_salary ?? '0'}}</li>
                            <li><strong>Traveling Allowance (Per Kilometer):</strong> Rs. {{$staff->bank->travelling_allowance ?? '0'}}</li>
                        </ul>
                    </div>
              </div>
            </div>
        </div>

        <!-- User ID Details -->
        <div class="col-lg-4">
            <div class="card p-4">
                <h5>User ID Back</h5>
                <div class="mb-3">
                @if ($staff && $staff->user_id_back)
                  <img src="{{asset('storage/'. $staff->user_id_back)}}" alt="User ID Front" class="img-fluid border rounded">   
                @endif
                </div>
                <h5>User ID Front</h5>
                <div class="mb-3">
                 @if ($staff && $staff->user_id_front)
                    <img src="{{asset('storage/'.$staff->user_id_front)}}" alt="User ID Back" class="img-fluid border rounded">    
                 @endif
                </div>
                <a href="{{route('staff.index')}}" class="btn btn-info btn-block">Back to Staff</a>
            </div>
        </div>
    </div>
</div>
