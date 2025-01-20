    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="block-heading m-0">Supplier List</h4> 
               
            <div class="input-group w-50 search-input-group">
                <input type="text" wire:model.debounce.500ms="search" class="form-control border" placeholder="Search here...">
                <button type="button" wire:click="$refresh" class="btn btn-outline-primary mb-0">
                    <span class="material-icons">search</span>
                </button>
            </div>
                
            <a href="{{ route('suppliers.add') }}" class="btn btn-cta btn-sm mb-0">
                <i class="material-icons text-white" style="font-size:15px;">add</i>
                Add Supplier
            </a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-body pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                @if (session()->has('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                               

                                @if (session()->has('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Name
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Email
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Phone
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                           Status
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                           Action
                                        </th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suppliers as $supplier)
                                            <tr>
                                                <td>
                                                    <div class="d-flex py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $supplier->name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $supplier->email ?? 'N/A'}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $supplier->mobile }}</p>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input ms-auto" type="checkbox" wire:click="toggleStatus({{ $supplier->id }})" 
                                                        @if ($supplier->status)
                                                            checked
                                                        @endif
                                                        >
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                <a href="{{ route('suppliers.details', $supplier->id) }}" class="btn btn-outline-dark custom-btn-sm mb-0" data-toggle="tooltip" data-original-title="View Details" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('suppliers.edit', $supplier->id) }}"  class="btn btn-outline-info custom-btn-sm mb-0" data-toggle="tooltip" data-original-title="Edit supplier" title="Edit Supplier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button wire:click="deleteSupplier({{ $supplier->id }})" class="btn btn-outline-danger custom-btn-sm mb-0" data-toggle="tooltip" data-original-title="Delete supplier" title="Delete Supplier">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                </td>
                                            </tr>
                                    @endforeach
                                </tbody>

                            </table>
                             {{-- {{ $suppliers->links() }} --}}
                        </div>
                        <div class="mt-3">
                            <nav aria-label="Page navigation">
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

