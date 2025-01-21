<div class="container-fluid px-2 px-md-4">
    <div class="card card-body">
        <div class="card card-plain h-100">
            <div class="card-header pb-0 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="m-0">Create Purchase Order</h4>
                    <a href="{{ route('purchase_order.index') }}" class="btn btn-cta">
                        <i class="material-icons text-white" style="font-size: 15px;">chevron_left</i>
                        Back
                    </a>
                </div>
            </div>

            <div class="card-body p-3">
                <form wire:submit.prevent="savePurchaseOrder">
                    <!-- Supplier Information -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="suppliers" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select wire:model="selectedSupplier" wire:change="SelectedSupplier($event.target.value)" id="suppliers" class="form-control form-control-sm border border-1 p-2">
                                <option value="" selected hidden>-- Select Supplier --</option>
                                @if (!empty($suppliers) && count($suppliers)>0)
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('suppliers')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if ($selectedSupplier)
                    <!-- Address Information -->
                    <div class="card mb-3 p-3" style="background-color:rgb(249, 252, 252)">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Supplier Billing Address</h6>
                                @php
                                    $supplier = $suppliers->firstWhere('id',$selectedSupplier);// Get the selected supplier
                                @endphp
                                @if ($supplier)
                                <p>
                                    <strong>Address</strong>: {{$supplier->billing_address}}<br>
                                    <strong>Landmark</strong>: {{$supplier->billing_landmark ?? '';}}<br>
                                    <strong>City</strong>: {{$supplier->billing_city}}<br>
                                    <strong>State</strong>: {{$supplier->billing_state}}<br>
                                    <strong>Country</strong>: {{$supplier->billing_country}}<br>
                                    <strong>Pincode</strong>: {{$supplier->billing_pin}}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Item Details -->
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <h6 class="badge bg-danger custom_danger_badge">Item Details</h6>
                        </div>
                        <div class="col-md-12">
                            <div class="card mb-3 p-3" style="background-color: rgb(249, 252, 252)">
                                @foreach ($rows as $index =>$row)
                                    <div class="row mb-3"> 
                                    <div class="col-md-3">
                                        <label for="collections_{{ $index }}" class="form-label"><strong>Collection</strong> <span class="text-danger">*</span></label>
                                        <select wire:model="rows.{{ $index }}.collections" wire:change="SelectedCollection({{ $index }},$event.target.value)" id="collections_{{$index}}" class="form-control form-control-sm border border-1 p-2" >
                                            <option value="" selected hidden>Select Collection</option>
                                            @if (!empty($collections))
                                                @foreach ($collections as $collection)
                                                    <option value="{{$collection['id']}}">{{$collection['title']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('collection')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if (!empty($row['fabrics']))
                                        <div class="col-md-3">
                                            <label for="fabric_{{$index}}" class="form-label"><strong>Fabric</strong> <span class="text-danger">*</span></label>
                                            <select wire:model="rows.{{$index}}.fabric" id="fabric_{{$index}}" class="form-control form-control-sm border border-1 p-2">
                                                <option value="" selected hidden>Select Fabric</option>
                                                @foreach ($row['fabrics'] as $fabric)
                                                    <option value="{{ $fabric['id'] }}">{{ $fabric['title'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('fabric')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @elseif(!empty($row['products']))
                                        <div class="col-md-3">
                                            <label for="product_{{$index}}" class="form-label"><strong>Product</strong> <span class="text-danger">*</span></label>
                                            <select type="text" wire:model="rows.{{$index}}.product" id="product_{{$index}}" class="form-control form-control-sm border border-1 p-2" placeholder="Search product by name">
                                                <option value="" selected hidden>Select Product</option>
                                                @if (!empty($row['products']) && count($row['products'])>0)
                                                    @foreach ($row['products'] as $product_item)
                                                        <option value="{{ $product_item['id'] }}">{{ $product_item['name'] }}</option>
                                                    @endforeach
                                                @else
                                                   <option value="" disabled>No products available</option>
                                                @endif
                                            </select>
                                            @error('product')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="col-md-1">
                                        @if($isFabricSelected[$index] ?? false)
                                            <label for="pcs_per_mtr_{{$index}}" class="form-label">Pcs per Mtr</label>
                                            <input type="number" wire:model="rows.{{$index}}.pcs_per_mtr" id="pcs_per_mtr_{{$index}}" class="form-control form-control-sm border border-1 p-2" value="1">
                                        @else
                                            <label for="pcs_per_qty_{{$index}}" class="form-label">Pcs per Qty</label>
                                            <input type="number" wire:model="rows.{{$index}}.pcs_per_qty" id="pcs_per_qty_{{$index}}" class="form-control form-control-sm border border-1 p-2" value="1">
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label for="price_per_pc_{{$index}}" class="form-label">Price/Pc (Inc. Tax)</label>
                                        <input type="text" wire:model="rows.{{$index}}.price_per_pc" id="price_per_pc_{{$index}}" class="form-control form-control-sm border border-1 p-2" placeholder="Product Cost Price">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="total_amount_{{$index}}" class="form-label">Total Amount</label>
                                        <input type="text" wire:model="rows.{{$index}}.total_amount" id="total_amount_{{$index}}" class="form-control form-control-sm border border-1 p-2" readonly>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        @if ($loop->first)
                                           <button type="button" class="btn btn-success btn-sm" wire:click="addRow"><i class="fa fa-plus"></i></button>
                                        @else  
                                        <button type="button" class="btn btn-success btn-sm" wire:click="addRow"><i class="fa fa-plus"></i></button>
                                           <button type="button" class="btn btn-danger btn-sm" wire:click="removeRow({{ $index }})"><i class="fa fa-times"></i></button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="row mb-3">
                        <div class="col-md-12 text-end">
                            <h6>Total Amount (Inc. Tax): <span>Rs 1000</span></h6>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button type="reset" class="btn btn-warning">Reset Form</button>
                            <button type="reset" class="btn btn-danger">Reset Items</button>
                            <button type="submit" class="btn btn-cta">Add</button>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
