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
                                        @forelse ($purchaseOrders as $purchaseOrder)
                                        <tr>
                                            <td><p class="text-xs font-weight-bold mb-0">{{ $purchaseOrder->created_at?->format('d-m-Y') ?? 'N/A' }}
                                            </p></td>
                                            <td>
                                                @foreach ($purchaseOrder->orderproducts() as $product)
                                                    <p>{{ $product->name }}</p>
                                                @endforeach
                                            </td>

                                            <td>
                                                <p>{{ $purchaseOrder->total_price }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $purchaseOrder->supplier->name }}</p>
                                            </td>
                                            <td>
                                                @if ($purchaseOrder->status == 0)
                                                    <p class ="text-danger"><span>Pending</span></p>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input ms-auto" type="checkbox" wire:click="toggleStatus({{ $purchaseOrder->id }})" 
                                                    @if ($purchaseOrder->status)
                                                        checked
                                                    @endif>
                                                </div>
                                            </td>
                                            <td class="align-middle action_tab">
                                                <a href="{{route('purchase_order.edit',$purchaseOrder->id)}}" class="btn btn-outline-info btn-sm custom-btn-sm mb-0" data-toggle="tooltip" data-original-title="Edit product">
                                                    <span class="material-icons">edit</span>
                                                </a>
                                                <button wire:click="deleteProduct({{ $purchaseOrder->id }})" class="btn btn-outline-danger btn-sm custom-btn-sm mb-0"><span class="material-icons">delete</span></button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
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


