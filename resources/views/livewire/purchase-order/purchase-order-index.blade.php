<div class="">
    <!-- Navbar -->
    <!-- End Navbar -->

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="block-heading m-0">PO</h4> 
                <div class="input-group w-50 search-input-group">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control border" placeholder="Search here...">
                    <button type="button" wire:click="$refresh" class="btn btn-outline-primary mb-0">
                        <span class="material-icons">search</span>
                    </button>
                </div>
                
            {{-- </div> --}}
            <a class="btn btn-cta btn-sm mb-0" href="{{route('purchase_order.create')}}" role="button" >
                <i class="material-icons text-white" style="font-size: 15px;">add</i>
                <span class="ms-1">Add New PO</span>
            </a>
        </div>
        <div class="row">
            <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body pb-0">
                            <!-- Display Success Message -->
                            @if (session('message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Display Error Message -->
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Ordered At</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Products</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Net Amount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Supplier</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @forelse ($products as $product)
                                        <tr>
                                            <td>
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    @if ($product->product_image)
                                                        <img src="{{ asset('storage/'.$product->product_image) }}" alt="" style="width: 50px; height: 50px;">
                                                    @else
                                                        <img src="{{asset('assets/img/cubes.png')}}" alt="no-img" style="width: 50px; height: 50px;">    
                                                    @endif
                                                </span>
                                            </td> --}}
                                          
                                            {{-- <td><h6 class="mb-0 text-sm">{{ ucwords($product->name) }}</h6></td>
                                            <td><p class="text-xs font-weight-bold mb-0">{{ ucwords($product->category->title ?? 'N/A') }}</p></td>
                                           
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input ms-auto" type="checkbox" wire:click="toggleStatus({{ $product->id }})" 
                                                    @if ($product->status)
                                                        checked
                                                    @endif>
                                                </div>
                                            </td>
                                            <td class="align-middle action_tab">
                                                <a href="{{route('product.update',$product->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm mb-0" data-toggle="tooltip" data-original-title="Edit product">
                                                    <span class="material-icons">edit</span>
                                                </a>
                                                <button wire:click="deleteProduct({{ $product->id }})" class="btn btn-outline-danger btn-sm custom-btn-sm mb-0"><span class="material-icons">delete</span></button>
                                                <a href="{{route('product.gallery',$product->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm mb-0">Gallery </a>
                                                <a href="{{ route('measurements.index',$product->id) }}" class="btn btn-outline-info btn-sm custom-btn-sm mb-0" title="">Measurement
                                                    <span class="count">18</span></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <p class="text-xs text-secondary mb-0">No products found.</p>
                                            </td>
                                        </tr>
                                        @endforelse --}}
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>


