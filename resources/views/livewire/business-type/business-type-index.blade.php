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
                                    <h5>Business Type</h5>
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
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Title</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($business_types as $k => $businessType)
                                            <tr>
                                                <td><h6 class="mb-0 text-sm">{{ $k + 1 }}</h6></td>
                                                <td><p class="text-xs font-weight-bold mb-0">{{ $businessType->title }}</p></td>
                                                <td class="align-middle">
                                                    <button wire:click="edit({{ $businessType->id }})" class="btn btn-outline-info btn-sm custom-btn-sm mb-0" title="Edit">
                                                        <span class="material-icons">edit</span>
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
                                <h5>{{$businessTypeId ? "Update Business Type" : "Create Business Type"}}</h5>  
                            </div>
                            <form wire:submit.prevent="{{ $businessTypeId ? 'updateBusinessType' : 'storeBusinessType' }}">
                                <div class="row">
                                    <label class="form-label"> Title</label>
                                    <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                        <input type="text" wire:model="title" class="form-control border border-2 p-2" placeholder="Enter Title">
                                    </div>
                                    @error('title')
                                        <p class='text-danger inputerror'>{{ $message }}</p>
                                    @enderror

                                    <div class="mb-2 text-end mt-4">
                                        <a href="" class="btn btn-cta btn-sm mt-1"><i class="material-icons text-white" style="font-size: 15px;">refresh</i>Refresh</a>
                                        <button type="submit" class="btn btn-cta btn-sm mt-1" wire:loading.attr="disabled">
                                            <span>{{ $businessTypeId ? 'Update' : 'Create' }}</span>
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