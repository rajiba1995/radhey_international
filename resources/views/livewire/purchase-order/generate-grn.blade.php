

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary">Generate GRN</h5>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-4 text-muted">PO: <span class="text-dark">{{ $purchaseOrder->unique_id }}</span></h6>
                 <a href="{{route('purchase_order.index')}}" class="btn btn-cta" ><i class="material-icons text-white" style="font-size: 15px;">chevron_left</i>Back</a>
            </div>
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>  
            @endif
            <form wire:submit.prevent="generateGrn">
                  {{-- Card for Fabrics --}}
                @if($purchaseOrder->orderproducts->where('collection_id', 1)->isNotEmpty())
                <div class="card mt-2">
                    <div class="card-header">
                        <h6 class="mb-0">Fabric Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr class="text-center">
                                    <td>Bulk In</td>
                                    <th>Collection</th>
                                    <th>Fabric Name</th>
                                    <th>Quantity (in meters)</th>
                                    <th>Unique Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrder->orderproducts as $orderProduct)
                                    @if ($orderProduct->collection_id == 1)
                                        <tr class="text-center">
                                            <td>
                                                <input type="checkbox" wire:model="selectedFabricBulkIn" value="{{ $orderProduct->id }}" wire:change="toggleFabricUniqueNumbers({{ $orderProduct->id }})">
                                            </td>
                                            <td>{{ $orderProduct->collection ? $orderProduct->collection->title : '' }}</td>
                                            <td>{{ $orderProduct->fabric ? $orderProduct->fabric->title : 'N/A' }}</td>
                                            <td>{{ intval($orderProduct->qty_in_meter) }}</td>
                                            <td>
                                                <input type="checkbox" wire:model="selectedFabricUniqueNumbers" value="{{ $orderProduct->id }}" disabled>
                                                {{ $fabricUniqueNumbers[$orderProduct->id] ?? '' }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                {{-- Card for Products --}}
                @if($purchaseOrder->orderproducts->where('collection_id', '!=', 1)->isNotEmpty())
                <div class="card mt-2">
                    <div class="card-header">
                        <h6 class="mb-0">Product Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr class="text-center">
                                    <th>Bulk In</th>
                                    <th>Collection</th>
                                    <th>Product Name</th>
                                    <th>Quantity (in pieces)</th>
                                    <th>Unique Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrder->orderproducts as $orderProduct)
                                    @if ($orderProduct->collection_id != 1)
                                        @php 
                                            $rowCount = $orderProduct->qty_in_pieces; 
                                        @endphp
                                        @for ($i = 0; $i < $rowCount; $i++)
                                            <tr class="text-center">
                                                @if ($i === 0)
                                                    <td rowspan="{{ $rowCount }}">
                                                        <input type="checkbox" 
                                                               wire:model="selectedBulkIn" 
                                                               value="{{ $orderProduct->id }}" 
                                                               wire:change="toggleUniqueNumbersForProduct({{ $orderProduct->id }}, {{ $rowCount }})">
                                                    </td>
                                                    <td rowspan="{{ $rowCount }}">{{ $orderProduct->collection ? $orderProduct->collection->title : '' }}</td>
                                                    <td rowspan="{{ $rowCount }}">{{ $orderProduct->product ? $orderProduct->product->name : '' }}</td>
                                                    <td rowspan="{{ $rowCount }}">{{ $orderProduct->qty_in_pieces }}</td>
                                                @endif
                                                <td>
                                                    @if(isset($selectedUniqueNumbers[$orderProduct->id]))
                                                        @if(in_array($i, $selectedUniqueNumbers[$orderProduct->id]))
                                                            <input type="checkbox" checked disabled>
                                                        @else
                                                            <input type="checkbox" disabled>
                                                        @endif
                                                    @else
                                                        <input type="checkbox" disabled>
                                                    @endif
                                                    @if(isset($productUniqueNumbers[$orderProduct->id]))
                                                        {{ $productUniqueNumbers[$orderProduct->id][$i] ?? '' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endfor
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-cta">Generate GRN</button>
                </div>
            </form>
        </div>
    </div>
</div>

