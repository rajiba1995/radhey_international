<div class="">
    <!-- Navbar -->
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
            <div class="nav-wrapper position-relative end text-end">
                <!-- Single Button -->
                {{-- <a class="btn btn-primary btn-sm" href="{{route('product.import')}}" role="button" >
                    <i class="material-icons text-white">file_upload</i>
                    <span class="ms-1">Import</span>
                </a> --}}
                <a class="btn btn-primary btn-sm" href="{{route('product.add')}}" role="button" >
                    <i class="material-icons text-white" style="font-size: 15px;">add</i>
                    <span class="ms-1">Create Product</span>
                </a>
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
                                                Published Date
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Name
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Category
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                SubCategory
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Action
                                            </th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                        <tr>
                                            <td>
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $product->created_at->format('d/m/Y') }}
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
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ ucwords($product->sub_category->title ?? 'N/A') }}
                                                </p>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input ms-auto" type="checkbox" wire:click="toggleStatus({{ $product->id }})" 
                                                    @if ($product->status)
                                                        checked
                                                    @endif>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{route('product.update',$product->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm" data-toggle="tooltip" data-original-title="Edit product">
                                                    <span class="material-icons">edit</span>
                                                </a>
                                                <button wire:click="deleteProduct({{ $product->id }})" class="btn btn-outline-danger btn-sm custom-btn-sm"><span class="material-icons">delete</span></button>
                                                <a href="{{route('product.galary',$product->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm">Galary</a>
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


