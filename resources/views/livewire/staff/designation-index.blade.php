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
                                <h6>Designation</h6>
                            </div>
                            {{-- <div class="col-lg-6 col-5 my-auto text-end">
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                                    <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                        <input type="text" wire:model.debounce.500ms="search" class="form-control border border-2 p-2 custom-input-sm" placeholder="Enter Title">
                                        <button type="button" wire:target="search" class="btn btn-dark text-light mb-0 custom-input-sm">
                                            <span class="material-icons">search</span>
                                        </button>
                                    </div>
                                        <!-- Optionally, add a search icon button -->
                                    
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Name</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Roles</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Count Staff</th>
                                        <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                            Status</th>
                                        <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($designations as $k => $designation)
                                    {{-- @dd($designation) --}}
                                        <tr>
                                            <td class="align-middle text-center">{{ucwords($designation->name)}}</td>
                                            <td class="align-middle text-center">{{ $designation->role_names }}</td>
                                            <td class="align-middle text-center"> {{ $designation->users_count > 0 ? $designation->users_count : 'No Roles Assigned' }}</td>
                                            <td class="align-middle text-sm" style="text-align: center;">
                                                <div class="form-check form-switch">
                                                    <input 
                                                        class="form-check-input ms-auto" 
                                                        type="checkbox" 
                                                        id="flexSwitchCheckDefault{{$designation->id}}" 
                                                        wire:click="toggleStatus({{$designation->id}})"
                                                        @if($designation->status) checked @endif
                                                    >
                                                </div>
                                            </td>
                                            <td class="align-middle text-end px-4">
                                                @if($designation->id>1)
                                                <button wire:click="edit({{$designation->id}})" class="btn btn-outline-info btn-sm custom-btn-sm" title="Edit"><span class="material-icons">edit</span></button>
                                                @endif
                                                {{-- <button wire:click="destroy({{ $category->id }})" class="btn btn-outline-danger btn-sm custom-btn-sm">Delete</button> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- {{ $designations->links('pagination::bootstrap-4') }} --}}
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
                        <form wire:submit.prevent="storeOrUpdate">
                            <div class="row">
                                <h4 class="page__subtitle">{{ $designationId ? 'Update Designation' : 'Add New Designation' }}</h4>
                                <label class="form-label">Name<span class="text-danger">*</span></label>
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                    <input type="text" wire:model="name" class="form-control border border-2 p-2" placeholder="Enter Name" value="{{ucwords($name)}}">
                                </div>
                                @error('name')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <!-- Roles Section -->
                                <div class="mb-3">
                                    <label class="form-label">Roles</label>
                                    <div class="row">
                                        @foreach ($roleList as $role)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="role{{$role->id}}" wire:model="roles" value="{{$role->id}}">
                                                    <label class="form-check-label" for="role{{$role->id}}">
                                                       {{$role->name}}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mb-2 text-end">
                                    <button type="submit" class="btn btn-primary btn-sm" 
                                            wire:loading.attr="disabled">
                                        <span> 
                                            {{ $designationId ? 'Update Designation' : 'Create Designation' }}
                                        </span>
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
