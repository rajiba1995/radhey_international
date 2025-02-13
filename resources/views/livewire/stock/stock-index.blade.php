<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="block-heading m-0">Stock Overview</h4>
        <!-- <div class="input-group w-50 search-input-group">
            <input
                type="text"
                wire:model.debounce.500ms="search"
                class="form-control border"
                placeholder="Search here..."
            />
            <button type="button" wire:click="$refresh" class="btn btn-outline-primary mb-0">
                <span class="material-icons">search</span>
            </button>
        </div> -->
    </div>

    <div class="d-flex justify-start gap-4 mb-4">
        <button
            class="btn btn-sm px-4 py-2 {{ $activeTab === 'product' ? 'btn-primary' : 'btn-outline-secondary' }}"
            wire:click="setActiveTab('product')"
        >
            Product Stock
        </button>
        <button
            class="btn btn-sm px-4 py-2 {{ $activeTab === 'fabric' ? 'btn-primary' : 'btn-outline-secondary' }}"
            wire:click="setActiveTab('fabric')"
        >
            Fabric Stock
        </button>
        

    </div>

    <div>
        @if ($activeTab === 'product')
            <div class="card">
                <div class="card-body">
                    <!-- <button wire:click="exportStockProduct" class="btn btn-success btn-sm">
                        Export to Excel
                    </button> -->
                    <!-- Export & Date Filters -->
                    <!-- <div class="d-flex align-items-center gap-2 mb-3">
                        <input type="date" wire:model="startDate" class="form-control w-auto" />
                        <input type="date" wire:model="endDate" class="form-control w-auto" />
                        <button wire:click="exportStockProduct" class="btn btn-success btn-sm">
                            Export Product CSV
                        </button>
                    </div> -->

                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <div class="position-relative">
                            <input type="text" wire:model="searchProduct"  class="searchInput form-control w-auto" placeholder="Search by Product Name" />
                        </div>
                        <input type="date" wire:model="startDateProduct" class="form-control w-auto" />
                        <input type="date" wire:model="endDateProduct" class="form-control w-auto" />
                        <button type="button" wire:click="$refresh" class="btn btn-outline-primary mb-0">
                            <span class="material-icons">search</span>
                        </button>
                        <button type="button" class="btn btn-outline-primary mb-0"
                            wire:click="clearProductFilters">
                            ✖
                        </button>
                        <button wire:click="exportStockProduct" class="btn btn-success btn-sm">
                            Export Product CSV
                        </button>
                    </div>

                    <h5 class="mb-3">Product Stock</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Order Quantity (Pieces)</th>
                                    <th>GRN Quantity (Pieces)</th>
                                    <th>Piece Price</th>
                                    <th>Total Price</th>
                                    <th>Entry Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $index => $product)
                                    <tr class="text-center">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->product->name ?? 'N/A' }}</td>
                                        <td>{{ intval($product->qty_in_pieces) }}</td>
                                        <td>{{ intval($product->qty_while_grn) }}</td>
                                        <td>Rs. {{ number_format($product->piece_price, 2) }}</td>
                                        <td>Rs. {{ number_format($product->total_price, 2) }}</td>
                                        <td> {{ date('d-m-Y',strtotime($product->created_at))}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No product stock available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                         <!-- Pagination -->
                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($activeTab === 'fabric')
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <input type="text" wire:model="searchFabric" class="form-control w-auto" placeholder="Search by Fabric Name" />
                        <input type="date" wire:model="startDateFabric" class="form-control w-auto" />
                        <input type="date" wire:model="endDateFabric" class="form-control w-auto" />
                        <button type="button" wire:click="$refresh" class="btn btn-outline-primary mb-0">
                            <span class="material-icons">search</span>
                        </button>
                        <button type="button" class="btn btn-outline-primary mb-0"
                            wire:click="clearFabricFilters">
                            ✖
                        </button>
                        <button wire:click="exportStockFabric" class="btn btn-success btn-sm">
                            Export Fabric CSV
                        </button>
                    </div>


                    <h5 class="mb-3">Fabric Stock</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Fabric Name</th>
                                    <th>Order Quantity (Meters)</th>
                                    <th>GRN Quantity (Meters)</th>
                                    <th>Piece Price</th>
                                    <th>Total Price</th>
                                    <th>Entry Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fabrics as $index => $fabric)
                                    <tr class="text-center">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $fabric->fabric->title ?? 'N/A' }}</td>
                                        <td>{{ intval($fabric->qty_in_meter) }}</td>
                                        <td>{{ intval($fabric->qty_while_grn) }}</td>
                                        <td>Rs. {{ number_format($fabric->piece_price, 2) }}</td>
                                        <td>Rs. {{ number_format($fabric->total_price, 2) }}</td>
                                        <td> {{ date('d-m-Y', strtotime($fabric->created_at))}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No fabric stock available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
