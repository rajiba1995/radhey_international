<div class="container-fluid px-2 px-md-4">
    <div class="card card-body">
        <h4 class="m-0">Create Staff</h4>
        <div class="card-header pb-0 p-3">
            <div class="row">
                  {{-- Supplier Information --}}
                  <div class="col-md-8 d-flex align-items-center">
                    <h6 class="badge bg-danger custom_danger_badge">Basic Information</h6>
                  </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('staff.index') }}" class="btn btn-dark btn-sm">
                        <i class="material-icons text-white" style="font-size: 15px;">chevron_left</i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-3">
            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="mb-3 col-md-4">
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
                
                    <div class="mb-3 col-md-4">
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
                        <input type="number" wire:model="mobile" id="mobile" class="form-control form-control-sm border border-1 p-2" placeholder="Staff mobile">
                        @error('mobile')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="aadhaar_number" class="form-label">Aadhaar Number </label>
                        <input type="number" wire:model="aadhaar_number" id="aadhaar_number" class="form-control form-control-sm border border-1 p-2" placeholder="Staff Aadhaar Number">
                        @error('aadhaar_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="whatsapp_no" class="form-label">WhatsApp <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center">
                            <input type="number" wire:model="whatsapp_no" id="whatsapp_no" class="form-control form-control-sm border border-1 p-2 me-2" placeholder="Staff WhatsApp No"  @if($is_wa_same) disabled @endif>
                           
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
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label for="user_id_front" class="form-label">User ID Front</label>
                        <input type="file" wire:model="user_id_front" id="user_id_front" class="form-control form-control-sm border border-1 p-2">
                        @error('user_id_front')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @if ($user_id_front)
                            <div class="mt-2">
                                <img src="{{ $user_id_front->temporaryUrl() }}" class="img-thumbnail" style="max-width: 100px; display: block;" />
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label for="user_id_back" class="form-label">User ID Back</label>
                        <input type="file" wire:model="user_id_back" id="user_id_back" class="form-control form-control-sm border border-1 p-2">
                        @error('user_id_back')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @if ($user_id_back)
                            <div class="mt-2">
                                <img src="{{ $user_id_back->temporaryUrl() }}" class="img-thumbnail" style="max-width: 100px; display: block;" />
                            </div>
                        @endif
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
                                <input type="hidden" wire:model="password" value="yourPasswordHere">
                                @error('country')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    <button type="submit" class="btn btn-dark mt-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
