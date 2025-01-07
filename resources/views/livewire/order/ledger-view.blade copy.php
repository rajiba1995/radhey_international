<div class="row mb-4">
    <!-- Left Column: Designation List -->
    <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
        <div class="card my-4">
            <div class="card-header pb-0">
                <div class="row">
                    <!-- Flash Message -->
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
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Roles</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Count Staff</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($designations as $designation)
                                <tr>
                                    <td class="align-middle text-center">{{ ucwords($designation->name) }}</td>
                                    <td class="align-middle text-center">{{ $designation->role_names }}</td>
                                    <td class="align-middle text-center">
                                        {{ $designation->users_count > 0 ? $designation->users_count : 'No Roles Assigned' }}
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="form-check form-switch">
                                            <input 
                                                class="form-check-input ms-auto" 
                                                type="checkbox" 
                                                id="flexSwitchCheckDefault{{$designation->id}}" 
                                                wire:click="toggleStatus({{ $designation->id }})"
                                                @if($designation->status) checked @endif
                                            >
                                        </div>
                                    </td>
                                    <td class="align-middle text-end px-4">
                                        <button wire:click="edit({{ $designation->id }})" class="btn btn-outline-info btn-sm" title="Edit">
                                            <span class="material-icons">edit</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No designations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Add or Update Designation -->
    <div class="col-lg-4 col-md-6 mb-md-0 mb-4">
        <div class="card my-4">
            <div class="card-body px-4 pb-2">
                <form wire:submit.prevent="storeOrUpdate">
                    <h4 class="page__subtitle">{{ $designationId ? 'Update Designation' : 'Add New Designation' }}</h4>
                    
                    <!-- Designation Name -->
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               wire:model="name" 
                               class="form-control border border-2 p-2 @error('name') is-invalid @enderror" 
                               placeholder="Enter Name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Roles Section -->
                    <div class="mb-3">
                        <label class="form-label">Roles</label>
                        <div class="row">
                            @foreach ($roleList as $role)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input 
                                            type="checkbox" 
                                            class="form-check-input" 
                                            id="role{{$role->id}}" 
                                            wire:model="roles" 
                                            value="{{ $role->id }}">
                                        <label class="form-check-label" for="role{{$role->id}}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="text-end">
                        <button type="button" class="btn btn-dark btn-sm" wire:click="refresh">
                            <i class="material-icons text-white" style="font-size: 15px;">refresh</i>
                            Refresh
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                            {{ $designationId ? 'Update Designation' : 'Create Designation' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
