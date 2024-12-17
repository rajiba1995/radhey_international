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
                                <h6>Fabrics</h6>
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
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable">
                                    @foreach ($fabrics as $k => $fabric)
                                        <tr data-id="{{ $fabric->id }}" class="handle">
                                            <td class="align-middle text-center">{{ $k + 1 }}</td>
                                            <td class="align-middle text-center">{{ $fabric->title }}</td>
                                            <td class="align-middle text-center">{{ $fabric->code }}</td>
                                            <td class="align-middle text-center">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" 
                                                        class="form-check-input ms-auto" 
                                                        wire:click="toggleStatus({{ $fabric->id }})" 
                                                        @if ($fabric->status) checked @endif>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <button wire:click="edit({{ $fabric->id }})" class="btn btn-outline-info custom-btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button wire:click="destroy({{ $fabric->id }})"  class="btn btn-outline-danger custom-btn-sm"><i class="fas fa-trash"></i></button>
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
                            <h5>{{ $fabricId ? 'Edit Fabric' : 'Create Fabric' }}</h5>
                        </div>
                        <form wire:submit.prevent="{{ $fabricId ? 'update' : 'store' }}">
                            <!-- Measurement Title -->
                            <div class="form-group mb-3">
                                <label for="title">Fabric Title</label>
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
                            
                            <!-- Short Code -->
                            <div class="form-group mb-3">
                                <label for="code">Short Code</label>
                                <input 
                                    type="text" 
                                    id="code" 
                                    wire:model="code" 
                                    class="form-control border border-2 p-2" 
                                    placeholder="Enter Code" 
                                    aria-describedby="codeHelp">
                                @error('code') 
                                    <small id="codeHelp" class="text-danger">{{ $message }}</small> 
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-sm btn-primary mt-3">
                                {{ $fabricId ? 'Update Fabric' : 'Create Fabric' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
</div>


