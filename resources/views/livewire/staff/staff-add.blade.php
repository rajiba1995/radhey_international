<div class="container-fluid px-2 px-md-4">
    <div class="card card-body">
        <h4 class="m-0">Create Staff</h4>
        <div class="card-header pb-0 p-3">
            <div class="row">
                  {{-- Supplier Information --}}
                  <div class="col-md-8 d-flex align-items-center">
                    <h6 class="badge bg-danger custom_danger_badge">Basic Information</h6>
                  </div>
                 <div class="col-md-4 d-flex justify-content-end align-items-center">
                    <div class="d-flex">
                        <select wire:change="SelectedCountry"  wire:model="selectedCountryId"   class="form-select me-2" aria-label="Default select example">
                            <option selected hidden>Select Country</option>
                           @foreach($Selectcountry as $countries)
                            <option value="{{$countries->id}}">{{$countries->title}}</option>
                           @endforeach
                        </select>
                       
                        <a href="{{ route('staff.index') }}" class="btn btn-cta btn-sm">
                            <i class="material-icons text-white" style="font-size: 15px;">chevron_left</i> Back
                        </a>
                    </div>
                 </div>
            </div>
        </div>

        <div class="card-body p-3">
            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="mb-3 col-md-3">
                        <label for="branch_id" class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <select wire:model="branch_id" id="branch_id" class="form-control form-control-sm border border-1 p-2">
                            <option value="" selected hidden>Select Branch</option>
                            @foreach ($branchNames as $branchName)
                              <option value="{{$branchName->id}}">{{ucwords($branchName->name)}}</option>   
                            @endforeach 
                        </select>
                        @error('branch_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-3">
                        <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                        <select wire:model="designation" id="designation" class="form-control form-control-sm border border-1 p-2">
                            <option value="" selected hidden>Select Designation</option>
                            @foreach ($designations as $designation)
                              <option value="{{$designation->id}}">{{ucwords($designation->name)}}</option>   
                            @endforeach
                           
                            <!-- Add more options as needed -->
                        </select>
                        @error('designation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                
                    <div class="mb-3 col-md-4">
                        <label for="person_name" class="form-label">Person Name <span class="text-danger">*</span></label>
                        <input type="text" wire:model="person_name" id="person_name" class="form-control form-control-sm border border-1 p-2" placeholder="Enter Person Name">
                        @error('person_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                
                    <div class="mb-3 col-md-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" wire:model="email" id="email" class="form-control form-control-sm border border-1 p-2" placeholder="Enter Email">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                        <input type="text" wire:model="mobile" id="mobile" class="form-control form-control-sm border border-1 p-2" placeholder="Staff Mobile No">
                        @error('mobile')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="aadhaar_number" class="form-label">Aadhaar Number 
                         @if($showAadhaarStar)
                           <span class="text-danger">*</span>   
                         @endif
                         </label>
                        <input type="text" wire:model="aadhaar_number" id="aadhaar_number" class="form-control form-control-sm border border-1 p-2" placeholder="Staff Aadhaar Number">
                        @error('aadhaar_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="whatsapp_no" class="form-label">WhatsApp <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center">
                            <input type="text" wire:model="whatsapp_no" id="whatsapp_no" class="form-control form-control-sm border border-1 p-2 me-2" placeholder="Staff WhatsApp No"  @if($is_wa_same) disabled @endif>
                           
                            <input type="checkbox" id="is_wa_same" wire:change="SameAsMobile" value="0" @if($is_wa_same) checked @endif>
                            <label for="is_wa_same" class="form-check-label ms-2">Same as Phone Number</label>
                        </div>
                        @error('whatsapp_no')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Image Upload Section -->
                    <div class="col-md-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" wire:model="image" id="image" class="form-control form-control-sm border border-1 p-2">
                        @error('image')
                           <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @if ($image)
                            <div class="mt-2">
                                <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail" style="max-width: 100px; display: block;" />
                            </div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <label for="passport_id_front" class="form-label">Passport ID Front</label>
                        <input type="file" wire:model="passport_id_front" id="passport_id_front" class="form-control form-control-sm border border-1 p-2">
                        @error('passport_id_front')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @if ($passport_id_front)
                            <div class="mt-2">
                                <img src="{{ $passport_id_front->temporaryUrl() }}" class="img-thumbnail" style="max-width: 100px; display: block;" />
                            </div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <label for="passport_id_back" class="form-label">Passport ID Back</label>
                        <input type="file" wire:model="passport_id_back" id="passport_id_back" class="form-control form-control-sm border border-1 p-2">
                        @error('passport_id_back')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @if ($passport_id_back)
                            <div class="mt-2">
                                <img src="{{ $passport_id_back->temporaryUrl() }}" class="img-thumbnail" style="max-width: 100px; display: block;" />
                            </div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <label for="passport_expiry_date" class="form-label">Passport Expiry Date</label>
                        <input type="date" wire:model="passport_expiry_date" id="passport_expiry_date" class="form-control form-control-sm border border-1 p-2">
                        @error('passport_expiry_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                {{-- Emergency Contact Information --}}
                <div class="col-md-8 mt-4 d-flex align-items-center">
                        <h6 class="badge bg-danger custom_danger_badge">Emergency Contact Information</h6>
                    </div>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <label class="form-label">Contact Name</label>
                                <input type="text" wire:model="emergency_contact_person" class="form-control form-control-sm border border-1 p-2" placeholder="Enter Emergency Contact Name">
                                @error('emergency_contact_person')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact Number -->
                            <div class="col-md-4">
                                <label class="form-label">Contact Number</label>
                                <input type="text" wire:model="emergency_mobile" class="form-control form-control-sm border border-1 p-2" placeholder="Enter Emergency Mobile Number">
                                @error('emergency_mobile')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Checkbox to Copy Contact Number to WhatsApp Number -->
                            {{-- <div class="col-md-4 d-flex align-items-center mt-4">
                                
                            </div> --}}

                            <!-- WhatsApp Number -->
                            <div class="col-md-4">
                                <label class="form-label">WhatsApp Number</label>
                                <div class="d-flex align-items-center">
                                    <input type="text" wire:model="emergency_whatsapp" class="form-control form-control-sm border border-1 p-2" placeholder="Enter Emergency WhatsApp Number" @if($same_as_contact)
                                        disabled
                                    @endif>
                                    <input type="checkbox" wire:change="SameAsContact" id="same_as_contact" class="me-2" value="0" @if($same_as_contact)
                                        checked
                                    @endif>
                                    <label for="same_as_contact" class="form-label">Same as Contact Number</label>
                                </div>
                                @error('emergency_whatsapp')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact Address -->
                            <div class="col-md-4">
                                <label class="form-label">Contact Address</label>
                                <textarea wire:model="emergency_address" class="form-control form-control-sm border border-1 p-2" placeholder="Enter Emergency Contact Address"></textarea>
                                @error('emergency_address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                <!-- Other Details -->
                    <div class="col-md-8 mt-4 d-flex align-items-center">
                        <h6 class="badge bg-danger custom_danger_badge">Account Information</h6>
                    </div>
                        <div class="row mt-4">
                            <!-- Banking Information -->
                            <div class="col-md-4">
                                <label class="form-label">A/C Holder Name</label>
                                <input type="text" wire:model="account_holder_name" class="form-control form-control-sm border border-1 p-2" placeholder="A/C Holder Name">
                                @error('account_holder_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bank Name</label>
                                <input type="text" wire:model="bank_name" class="form-control form-control-sm border border-1 p-2" placeholder="Bank Name">
                                @error('bank_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Branch Name</label>
                                <input type="text" wire:model="branch_name" class="form-control form-control-sm border border-1 p-2" placeholder="Branch Name">
                                @error('branch_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label">Account No</label>
                                <input type="number" wire:model="account_no" class="form-control form-control-sm border border-1 p-2" placeholder="Account No">
                                @error('account_no')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label">IFSC</label>
                                <input type="text" wire:model="ifsc" class="form-control form-control-sm border border-1 p-2" placeholder="IFSC">
                                @error('ifsc')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Salary and Allowance -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <label class="form-label">Salary (In 30 days) <span class="text-danger">*</span></label>
                                <input type="number" wire:model="monthly_salary" class="form-control form-control-sm border border-1 p-2" placeholder="Salary (30 Days)">
                                @error('monthly_salary')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Salary (Per Day)</label>
                                <input type="number" wire:model="daily_salary" class="form-control form-control-sm border border-1 p-2" placeholder="Salary (Per Day)">
                                @error('daily_salary')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Travelling Allowance (Per KM)</label>
                                <input type="number" wire:model="travel_allowance" class="form-control form-control-sm border border-1 p-2" placeholder="Travel Allowance">
                                @error('travel_allowance')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-8 mt-4 d-flex align-items-center">
                            <h6 class="badge bg-danger custom_danger_badge">Address Information</h6>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="form-label">Address</label>
                                <textarea  wire:model="address" class="form-control form-control-sm border border-1 p-2" placeholder="Address"></textarea>
                                @error('address')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Landmark</label>
                                <input type="text" wire:model="landmark" class="form-control form-control-sm border border-1 p-2" placeholder="Landmark">
                                @error('landmark')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" wire:model="state" class="form-control form-control-sm border border-1 p-2" placeholder="State">
                                @error('state')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" wire:model="city" class="form-control form-control-sm border border-1 p-2" placeholder="City">
                                @error('city')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pincode</label>
                                <input type="number" wire:model="pincode" class="form-control form-control-sm border border-1 p-2" placeholder="Pincode">
                                @error('pincode')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Country</label>
                                <input type="text" wire:model="country" class="form-control form-control-sm border border-1 p-2" placeholder="Country">
                                {{-- <input type="hidden" wire:model="password" value="yourPasswordHere"> --}}
                                @error('country')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    <button type="submit" class="btn btn-cta mt-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
