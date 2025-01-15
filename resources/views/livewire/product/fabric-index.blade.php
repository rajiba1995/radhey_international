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
                            <table class="table align-items-center mb-0" >
                                <thead>
                                    <tr>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    @foreach ($fabrics as $fabric)
                                  
                                        <tr data-id="{{ $fabric->id }}" class="handle">
                                            <td class="align-middle text-center">
                                             @if ($fabric->image)
                                                 <img src="{{ asset($fabric->image) }}" alt="Fabric Image" width="70" style="border-radius: 10px;">
                                             @else
                                                 <img src="{{ asset('assets/img/fabric.webp') }}" alt="Fabric Image" width="70" style="border-radius: 10px;">
                                             @endif
                                            </td>
                                            <td class="align-middle text-center">{{ ucwords($fabric->title) }}</td>
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
                            <div class="d-flex justify-content-end mt-2">
                                {{-- {{$fabrics->links()}} --}}
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
                            <h5>{{ $fabricId ? 'Update Fabric' : 'Create Fabric' }}</h5>
                        </div>
                        <form wire:submit.prevent="{{ $fabricId ? 'update' : 'store' }}">
                            <!-- Measurement Title -->
                            <div class="form-group mb-3">
                                <input type="hidden" wire:model="product_id" id="product_id">
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
                            <div class="form-group mb-3">
                                <label for="threshold_price">Threshold Price</label>
                                <input 
                                    type="number" 
                                    id="threshold_price" 
                                    wire:model="threshold_price" 
                                    class="form-control border border-2 p-2" 
                                    placeholder="Enter Theresh Hold Price" 
                                    aria-describedby="thresholdpriceHelp">
                                @error('threshold_price') 
                                    <small id="thresholdpriceHelp" class="text-danger">{{ $message }}</small> 
                                @enderror
                            </div>
                            <!--  Code -->
                            <div class="form-group mb-3">
                                <label for="image">Color Image</label>
                                <input 
                                    type="file" 
                                    id="image" 
                                    wire:model="image" 
                                    class="form-control border border-2 p-2" 
                                    aria-describedby="imageHelp">
                                    @if(is_object($image))
                                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" width="100">
                                    @elseif ($fabricId)
                                        <!-- Show existing image if no new image is uploaded -->
                                        <img src="{{ asset($fabrics->where('id', $fabricId)->first()->image ?? '') }}" alt="Current Image" width="100">
                                    @endif
                                @error('image') 
                                    <small id="imageHelp" class="text-danger">{{ $message }}</small> 
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end">
                                <a href="{{route('product.view')}}" class="btn btn-dark btn-sm mt-2">
                                    <i class="material-icons text-white" style="font-size: 15px;">chevron_left</i> 
                                    Back
                                </a>
                                <button type="submit" class="btn btn-sm btn-primary mt-2">
                                    {{ $fabricId ? 'Update Fabric' : 'Create Fabric' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
</div>


