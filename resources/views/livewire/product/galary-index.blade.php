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
                                <h6>Galary</h6>
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
                                            SL</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Title</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Status</th>
                                        <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach($categories as $k => $category)
                                        <tr>
                                            <td class="align-middle text-center">{{ $k + 1 }}</td>
                                            <td class="align-middle text-center">{{ ucwords($category->title) }}</td>
                                            <td class="align-middle text-sm" style="text-align: center;">
                                                <div class="form-check form-switch">
                                                    <input 
                                                        class="form-check-input ms-auto" 
                                                        type="checkbox" 
                                                        id="flexSwitchCheckDefault{{ $category->id }}" 
                                                        wire:click="toggleStatus({{ $category->id }})"
                                                        @if($category->status) checked @endif
                                                    >
                                                </div>
                                            </td>
                                            <td class="align-middle text-end px-4">
                                                <button wire:click="edit({{ $category->id }})" class="btn btn-outline-info btn-sm custom-btn-sm" title="Edit"><span class="material-icons">edit</span></button>
                                                <button wire:click="destroy({{ $category->id }})" class="btn btn-outline-danger btn-sm custom-btn-sm" title="Delete"><span class="material-icons">delete</span></button>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-2">
                                {{-- {{ $categories->links('pagination::bootstrap-4') }} --}}
                            </div>
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
                            <h5>Create Galary</h5>  
                        </div>
                        <form wire:submit.prevent="">
                            <div class="row">
                                <label class="form-label">Images</label>
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                    <input type="hidden" wire:model="product_id" id="product_id">
                                    <input type="file" wire:model="images" class="form-control border border-2 p-2" placeholder="Choose Images" multiple>
                                </div>
                                @error('images')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                <div class="mb-2 text-end">
                                    <button type="submit" class="btn btn-primary btn-sm mt-1" 
                                            wire:loading.attr="disabled">
                                        <span> 
                                           Submit
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
