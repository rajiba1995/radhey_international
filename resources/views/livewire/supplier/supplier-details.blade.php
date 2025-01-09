<div>
    <!-- <h4>Supplier Details</h4> -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <!-- Paid Status -->
                        @if($supplier->status == 1)
                        <span class="badge bg-success me-2 ms-2 rounded-pill">Active</span>
                        @else
                        <span class="badge bg-warning me-2 ms-2 rounded-pill">Inactive</span>
                        @endif
                    </div>
                    <p class="mt-1 mb-3">
                       <!-- ccsc</span> -->
                    </p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-2">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-dark btn-sm mt-3"><i
                            class="material-icons text-white" style="font-size: 15px;">chevron_left</i>
                        Back</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card mb-4" style="height: 400px;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-1">Supplier Details</h5>
                                <h6 class="mb-1">
                                  <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-info custom-btn-sm">Edit</a>
                                </h6>
                            </div>
                            <div class="d-flex justify-content-start align-items-center mb-4">
                               
                                <div class="d-flex flex-column">
                                    <a href="">
                                        <h6 class="mb-0">{{ $supplier->name }}</h6>
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Contact info</h6>
                            </div>
                            <p class="mb-1"><i class="fas fa-envelope" style="font-size: 14px; color: #6c757d;"></i>
                            {{ $supplier->email }}
                            </p>
                            
                            <p class="mb-0"><i class="fas fa-phone" style="font-size: 14px; color: #6c757d;"></i>
                            {{ $supplier->mobile }}
                            </p>
                            <p class="mb-0"> <i class="fab fa-whatsapp" style="font-size: 14px; color: #25D366;"></i>
                            {{ $supplier->is_wa_same ? $supplier->mobile : $supplier->whatsapp_no }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                        <h5 class="card-title">Billing Address</h5>
                            <p> {{ $supplier->billing_address }} , {{ $supplier->billing_landmark }} , {{ $supplier->billing_city }} ,  {{ $supplier->billing_state }} , {{ $supplier->billing_country }}  -{{ $supplier->billing_pin }} </p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Account Information</h5>
                            <!-- GST Certificate -->
                            @if($supplier->gst_number !=""||$supplier->credit_limit !=""||$supplier->credit_days !="")
                            @if ($supplier->gst_file)
                            <h6>GST Certificate:</h6>
                                <img src="{{ asset($this->existingGstFile) }}" alt="GST Certificate" class="img-thumbnail" width="200">
                            @else
                                <p>No GST certificate uploaded.</p>
                            @endif
                            @if($supplier->gst_number)
                            <p class="mb-1"><i class="fas fa-id-card" style="font-size: 14px; color: #6c757d;"></i>
                                {{$supplier->gst_number}}
                            </p>
                                @endif 
                                @if($supplier->credit_limit)
                            <p class="mb-1"><i class="fas fa-credit-card" style="font-size: 14px; color: #6c757d;"></i>
                                {{$supplier->credit_limit}}
                            </p>
                            @endif 
                            @if($supplier->credit_days)
                            <p class="mb-1"><i class="fas fa-calendar-day" style="font-size: 14px; color: #6c757d;"></i>
                                {{$supplier->credit_days}}
                            </p>
                            @endif 

                            @else
                            <div class="card-body">
                                <p class="mb-1 text-muted">
                                    <i class="fas fa-info-circle" style="font-size: 14px; color: #6c757d;"></i>
                                    No information found.
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
