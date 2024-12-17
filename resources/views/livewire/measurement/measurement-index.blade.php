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
                                <h6>Measurements</h6>
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
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            SL</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Title</th>
                                        <!-- <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle"> -->
                                            <!-- measurement</th> -->
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Short Code</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable">
                                    @foreach ($measurements as $k => $measurement)
                                        <tr data-id="{{ $measurement->id }}" class="handle">
                                            <td class="align-middle text-center">{{ $k + 1 }}</td>
                                            <td class="align-middle text-center">{{ $measurement->title }}</td>
                                            <td class="align-middle text-center">{{ $measurement->short_code }}</td>
                                            <td class="align-middle text-center">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" 
                                                        class="form-check-input ms-auto" 
                                                        wire:click="toggleStatus({{ $measurement->id }})" 
                                                        @if ($measurement->status) checked @endif>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <button wire:click="edit({{ $measurement->id }})" class="btn btn-outline-info custom-btn-sm"> <i class="fas fa-edit"></i></button>
                                                <button wire:click="destroy({{ $measurement->id }})" class="btn btn-danger btn-sm">Delete</button>
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
                            <h5>{{ $measurementId ? 'Edit Measurement' : 'Create Measurement' }}</h5>
                        </div>
                        <form wire:submit.prevent="{{ $measurementId ? 'update' : 'store' }}">
                            <!-- Measurement Title -->
                            <div class="form-group mb-3">
                                <label for="title">Measurement Title</label>
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
                                <label for="short_code">Short Code</label>
                                <input 
                                    type="text" 
                                    id="short_code" 
                                    wire:model="short_code" 
                                    class="form-control border border-2 p-2" 
                                    placeholder="Enter Short Code" 
                                    aria-describedby="shortCodeHelp">
                                @error('short_code') 
                                    <small id="shortCodeHelp" class="text-danger">{{ $message }}</small> 
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-sm btn-primary mt-3">
                                {{ $measurementId ? 'Update Measurement' : 'Create Measurement' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
</div>
<!-- push('js') -->



</script>
