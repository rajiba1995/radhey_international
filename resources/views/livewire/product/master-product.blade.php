<div class="">
    <!-- Navbar -->
    <!-- End Navbar -->
    <style>



    </style>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h4 class="m-0">Product List</h4> 
            <div class="d-flex align-items-center">
                <!-- Single Button -->
                {{-- <a class="btn btn-primary btn-sm" href="{{route('product.import')}}" role="button" >
                    <i class="material-icons text-white">file_upload</i>
                    <span class="ms-1">Import</span>
                </a> --}}
                <a class="btn btn-primary btn-sm" href="{{route('product.add')}}" role="button" >
                    <i class="material-icons text-white" style="font-size: 15px;">add</i>
                    <span class="ms-1">Create Product</span>
                </a>
                <div class="input-group custom-input-group">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control border border-2 p-2 custom-input-sm" placeholder="Search here...">
                    <button type="button" wire:click="$refresh" class="btn btn-dark text-light mb-0 custom-input-sm">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
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
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Image
                                            </th>
                                            {{-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Collection
                                            </th> --}}
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Name
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Category
                                            </th>
                                            {{-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                SubCategory
                                            </th> --}}
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                        <tr>
                                            <td>
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    @if ($product->product_image)
                                                        <img src="{{ asset('storage/'.$product->product_image) }}" alt="" style="width: 50px; height: 50px;">
                                                    @else
                                                        <img src="{{asset('assets/img/cubes.png')}}" alt="no-img" style="width: 50px; height: 50px;">    
                                                    @endif
                                                </span>
                                            </td>
                                            
                                            <td>
         
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ ucwords($product->name) }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ ucwords($product->category->title ?? 'N/A') }}
                                                </p>
                                            </td>
                                            {{-- <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ ucwords($product->sub_category->title ?? 'N/A') }}
                                                </p>
                                            </td> --}}
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input ms-auto" type="checkbox" wire:click="toggleStatus({{ $product->id }})" 
                                                    @if ($product->status)
                                                        checked
                                                    @endif>
                                                </div>
                                            </td>
                                            <td class="align-middle action_tab text-center">
                                                <a href="{{route('product.update',$product->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm" data-toggle="tooltip" data-original-title="Edit product">
                                                    <span class="material-icons">edit</span>
                                                </a>
                                                <button wire:click="deleteProduct({{ $product->id }})" class="btn btn-outline-danger btn-sm custom-btn-sm"><span class="material-icons">delete</span></button>
                                                <a href="{{route('product.gallery',$product->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm">Gallery </a>
                                                {{-- <a href="{{route('product.fabrics',$product->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm">Fabrics <span class="count">18</span></a> --}}
                                                <a href="{{ route('measurements.index',$product->id) }}" class="btn btn-outline-info btn-sm custom-btn-sm" title="">Measurement
                                                    <span class="count">18</span></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <p class="text-xs text-secondary mb-0">No products found.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>


