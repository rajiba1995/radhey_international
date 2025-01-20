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
                                    <h5>Collections</h5>
                                </div>
                                <div class="col-lg-6 col-5 my-auto text-end">
                                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                                        <!-- Optionally, add a search icon button -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="sortableTable">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">SL</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Title</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sortable">
                                        @foreach ($collections as $k => $collection)
                                            <tr data-id="{{ $collection->id }}" class="handle">
                                                <td><h6 class="text-sm mb-0">{{ $k + 1 }}</h6></td>
                                                <td><p class="text-xs font-weight-bold mb-0">{{ $collection->title }}</p></td>
                                                <td class="align-middle">
                                                    <button wire:click="edit({{ $collection->id }})" class="btn btn-outline-info custom-btn-sm mb-0">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- Pagination Links -->
                                <div class="d-flex justify-content-center mt-3">
                                    {{$collections->links()}}
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
                                <h5>{{ $collectionId ? 'Update Collection' : 'Create Collection' }}</h5>
                            </div>
                            <form wire:submit.prevent="{{ $collectionId ? 'update' : 'store' }}">
                                <div class="form-group mb-3">
                                    <label for="title">Collection Title <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        id="title" 
                                        wire:model="title" 
                                        class="form-control border border-2 p-2" 
                                        placeholder="Enter Title" 
                                        aria-describedby="titleHelp">
                                    @error('title') 
                                        <small id="titleHelp" class="text-danger">{{ $message }}</small> 
                                    @enderror
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm btn-cta mt-2">
                                        {{ $collectionId ? 'Update Collection' : 'Create Collection' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>