<div class="container-fluid px-2 px-md-4">
    <div class="row mb-4">
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header pb-0">
                            <div class="row">
                                @if(session()->has('message'))
                                    <div class="alert alert-success" id="flashMessage">
                                        {{ session('message') }}
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-7">
                                    <h5>Branch</h5>
                                </div>
                                <div class="col-lg-6 col-5 my-auto text-end">
                                    <div class="input-group w-100 search-input-group">
                                        <input type="text" wire:model.debounce.500ms="search" class="form-control border" placeholder="Enter Title">
                                        <button type="button" wire:target="search" class="btn btn-outline-primary mb-0">
                                            <span class="material-icons">search</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">SL</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Email</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Mobile</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($branchNames as $k => $branchName)
                                            <tr>
                                                <td><h6 class="mb-0 text-sm">{{ $k + 1 }}</h6></td>
                                                <td><p class="text-xs font-weight-bold mb-0">{{ $branchName->name }}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">{{ $branchName->email }}</p></td>
                                                <td><p class="text-xs font-weight-bold mb-0">{{ $branchName->mobile }}</p></td>
                                                <td class="align-middle">
                                                    <button wire:click="edit({{ $branchName->id }})" class="btn btn-outline-primary select-md btn_action btn_outline" title="Edit">Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-md-0 mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body px-0 pb-2 mx-4">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>{{$branchId ? "Update Branch" : "Create Branch"}}</h5>  
                            </div>
                            <form wire:submit.prevent="{{ $branchId ? 'updateBranch' : 'storeBranch' }}">
                                <div class="row">
                                    <label class="form-label"> Branch Name</label>
                                    <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                        <input type="text" wire:model="name" class="form-control form-control-sm border border-2 p-2" placeholder="Enter Branch Name">
                                    </div>
                                    @error('name')
                                        <p class='text-danger inputerror'>{{ $message }}</p>
                                    @enderror
                                     <!-- Email -->
                                    <label class="form-label mt-3">Email</label>
                                    <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                        <input type="email" wire:model="email" class="form-control form-control-sm border border-2 p-2" placeholder="Enter Email">
                                    </div>
                                    @error('email')
                                        <p class='text-danger inputerror'>{{ $message }}</p>
                                    @enderror

                                    <!-- Mobile -->
                                    <label class="form-label mt-3">Mobile</label>
                                    <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                        <input type="number" wire:model="mobile" class="form-control form-control-sm border border-2 p-2" placeholder="Enter Mobile Number">
                                    </div>
                                    @error('mobile')
                                        <p class='text-danger inputerror'>{{ $message }}</p>
                                    @enderror

                                    <!-- WhatsApp -->
                                    <label class="form-label mt-3">WhatsApp</label>
                                    <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                        <input type="number" wire:model="whatsapp" class="form-control form-control-sm border border-2 p-2" placeholder="Enter WhatsApp Number">
                                    </div>
                                    @error('whatsapp')
                                        <p class='text-danger inputerror'>{{ $message }}</p>
                                    @enderror

                                    <!-- City -->
                                    <label class="form-label mt-3">City</label>
                                    <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                        <input type="text" wire:model="city" class="form-control form-control-sm border border-2 p-2" placeholder="Enter City">
                                    </div>
                                    @error('city')
                                        <p class='text-danger inputerror'>{{ $message }}</p>
                                    @enderror

                                    <!-- Address -->
                                    <label class="form-label mt-3">Address</label>
                                    <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                        <textarea type="text" wire:model="address" class="form-control form-control-sm border border-2 p-2" placeholder="Enter Address"></textarea>
                                    </div>
                                    @error('address')
                                        <p class='text-danger inputerror'>{{ $message }}</p>
                                    @enderror
                                    <div class="mb-2 text-end mt-4">
                                        <a href="" class="btn btn-cta btn-sm mt-1"><i class="material-icons text-white" style="font-size: 15px;">refresh</i>Refresh</a>
                                        <button type="submit" class="btn btn-cta btn-sm mt-1" wire:loading.attr="disabled">
                                            <span>{{ $branchId ? 'Update' : 'Create' }}</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>