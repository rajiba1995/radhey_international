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
                                <h6>Recurring Expense</h6>
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                                    <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                        <input type="text" wire:model.debounce.500ms="search" class="form-control border border-2 p-2 custom-input-sm" placeholder="Enter Title">
                                        <button type="button" wire:target="search" class="btn btn-dark text-light mb-0 custom-input-sm">
                                            <span class="material-icons">search</span>
                                        </button>
                                    </div>
                                        <!-- Optionally, add a search icon button -->
                                    
                                </div>
                            </div>
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
                                            Debit Purpose</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Credit Purpose</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            For Store</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            For Staff</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            For Partner</th>
                                        <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                            Status</th>
                                        {{-- <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                            Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach($designations as $k => $designation)
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
                                                <button wire:click="edit({{$designation->id}})" class="btn btn-outline-info btn-sm custom-btn-sm">Edit</button>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                            {{-- {{ $designations->links() }} --}}
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
                        <form wire.submit.prevent="saveExpense">
                            @csrf
                                <h4 class="page__subtitle">Add New</h4>
                                <input type="hidden" name="parent_id" value="{{$parent_id}}">                         
                                <div class="form-group mb-3">
                                    <label class="label-control">Title <span class="text-danger">*</span> </label>
                                    <input type="text" name="title" placeholder="Enter Expense Title" class="form-control border border-2 p-2" value="{{old('title')}}" >
                                    @error('title') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label-control">Description </label>
                                    <textarea name="description" placeholder="Enter Expense Description" class="form-control border border-2 p-2" rows="5">{{old('description')}}</textarea>
                                    @error('description') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div> 
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="for_debit" id="for_debit">
                                                <label class="form-check-label" for="for_debit">
                                                  Debit Purpose
                                                </label>
                                            </div>
                                            @error('for_debit') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>  
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="for_credit" id="for_credit">
                                                <label class="form-check-label" for="for_credit">
                                                  Credit Purpose
                                                </label>
                                            </div>
                                            @error('for_credit') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="for_staff" id="for_staff">
                                                <label class="form-check-label" for="for_staff">
                                                  For Staff
                                                </label>
                                            </div>
                                            @error('for_staff') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>  
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="for_store" id="for_store">
                                                <label class="form-check-label" for="for_store">
                                                  For Store
                                                </label>
                                            </div>
                                            @error('for_store') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>  
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="for_partner" id="for_partner">
                                                <label class="form-check-label" for="for_partner">
                                                  For Partner
                                                </label>
                                            </div>
                                            @error('for_partner') <p class="small text-danger">{{ $message }}</p> @enderror
                                        </div>  
                                    </div>
                                </div>                       
                                                        
                                <div class="mb-2 text-end">
                                    <button type="submit" class="btn btn-primary btn-sm mt-1" 
                                            wire:loading.attr="disabled">
                                        <span> 
                                            {{-- {{ $categoryId ? 'Update Category' : 'Create Category' }} --}}
                                        </span>
                                    </button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
