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
                                <h6>Collections</h6>
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                                    <!-- Optionally, add a search icon button -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="sortableTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Short Code</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable">
                                    @foreach ($collections as $k => $collection)
                                        <tr data-id="{{ $collection->id }}" class="handle">
                                            <td class="align-middle text-center">{{ $k + 1 }}</td>
                                            <td class="align-middle text-center">{{ $collection->type ? $collection->type->title : "N/A" }}</td>
                                            <td class="align-middle text-center">{{ $collection->title }}</td>
                                            <td class="align-middle text-center">{{ $collection->short_code }}</td>
                                            <td class="align-middle text-center">
                                                <button wire:click="edit({{ $collection->id }})" class="btn btn-outline-info custom-btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button wire:click="destroy({{ $collection->id }})" class="btn btn-outline-danger custom-btn-sm">
                                                    <i class="fas fa-trash"></i>
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
                                <label for="collection_type">Collection Type <span class="text-danger">*</span></label>
                                <select wire:model="collection_type" class="form-control border border-2 p-2" wire:click="changeType($event.target.value)">
                                    <option value="" selected hidden>Select type</option>
                                    @foreach ($collectionsType as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                                @error('collection_type') 
                                    <small class="text-danger">{{ $message }}</small> 
                                @enderror
                            </div>
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
                            @if($collection_type == 1)
                                <div class="form-group mb-3">
                                    <label for="short_code">Short Code</label>
                                    <input 
                                        type="text" 
                                        id="short_code" 
                                        wire:model="short_code" 
                                        class="form-control border border-2 p-2" 
                                        placeholder="Enter Short Code" 
                                        aria-describedby="codeHelp">
                                    @error('short_code') 
                                        <small id="codeHelp" class="text-danger">{{ $message }}</small> 
                                    @enderror
                                </div>
                            @endif
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary mt-2">
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
