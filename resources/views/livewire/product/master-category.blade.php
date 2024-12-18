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
                                <h6>Categories</h6>
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
                                        SL
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Image
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Title
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Status
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $k => $category)
                                    <tr>
                                        <td class="align-middle text-center">{{ $k + 1 }}</td>
                                        <td class="align-middle text-center">
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}"  class="img-thumbnail" width="50">
                                            @else
                                                <span class="text-secondary">No Image</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">{{ ucwords($category->title) }}</td>
                                        <td class="align-middle text-sm text-center">
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
                                            <button wire:click="edit({{ $category->id }})" class="btn btn-outline-info btn-sm custom-btn-sm" title="Edit">
                                                <span class="material-icons">edit</span>
                                            </button>
                                            <button wire:click="destroy({{ $category->id }})" class="btn btn-outline-danger btn-sm custom-btn-sm" title="Delete">
                                                <span class="material-icons">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                            <div class="d-flex justify-content-end mt-2">
                                {{ $categories->links('pagination::bootstrap-4') }}
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
                            <h5>Create Category</h5>  
                        </div>
                        <form wire:submit.prevent="{{ $categoryId ? 'update' : 'store' }}">
                            <div class="row">
                                <label class="form-label">Category Title</label>
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                    <input type="text" wire:model="title" class="form-control border border-2 p-2" placeholder="Enter Title">
                                </div>
                                @error('title')
                                    <p class='text-danger inputerror'>{{ $message }}</p>
                                @enderror

                                <label class="form-label mt-3">Category Image</label>
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                    <input type="file" wire:model="image" class="form-control border border-2 p-2">
                                </div>
                                @error('image')
                                    <p class='text-danger inputerror'>{{ $message }}</p>
                                @enderror

                                @if ($image)
                                    <div class="mt-2">
                                        <label class="form-label">Image Preview:</label>
                                        <img src="{{ $image instanceof \Livewire\TemporaryUploadedFile ? $image->temporaryUrl() : asset('storage/' . $image) }}" 
                                            alt="Preview" class="img-thumbnail" width="100">
                                    </div>
                                @endif

                                <div class="mb-2 text-end mt-4">
                                    <button type="submit" class="btn btn-primary btn-sm mt-1" wire:loading.attr="disabled">
                                        <span>{{ $categoryId ? 'Update Category' : 'Create Category' }}</span>
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
