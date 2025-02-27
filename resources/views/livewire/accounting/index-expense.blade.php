
    <!-- Navbar -->
    <!-- End Navbar -->
    <!-- <div class="container-fluid py-4"> -->
    <div class="container">
        <section class="admin__title">
            <h5>Expenses List</h5>
        </section>
        <section>
            <div class="search__filter">
                <div class="row align-items-center justify-content-end">
                    <div class="col-auto">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-auto mt-3">
                                <a href="{{ route('admin.accounting.add_depot_expense') }}" class="btn btn-outline-success select-md">Add Expense</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                        <!-- <p class="text-sm font-weight-bold">Items</p> -->
                    </div>
                    <div class="col-auto">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto mt-0">
                                <input type="text" wire:model="search" class="form-control select-md bg-white" id="customer"
                                    placeholder="Search Customers" value=""
                                    style="width: 350px;"  wire:keyup="FindCustomer($event.target.value)">
                            </div>
                    
                            <div class="col-auto mt-3">
                                <button type="button" wire:click="resetForm" class="btn btn-outline-danger select-md">Clear</button>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-outline-primary select-md" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="fas fa-file-csv me-1"></i> Import
                                </button>
                            </div>
                          
                            <div class="col-auto">
                                <button wire:click="sampleExport" class="btn btn-outline-success select-md"><i class="fas fa-file-csv me-1"></i>Sample CSV Download</button>
                            </div>
                            <div class="col-auto">
                                <button wire:click="export" class="btn btn-outline-success select-md"><i class="fas fa-file-csv me-1"></i>Export</button>
                            </div>
                            <!-- Import Modal -->
                            <div wire:ignore.self class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="importModalLabel">Import CSV File</h5>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Display Success/Error Messages -->
                                            @if (session()->has('success'))
                                                <div class="alert alert-success">{{ session('success') }}</div>
                                            @endif
                                            @if (session()->has('error'))
                                                <div class="alert alert-danger">{{ session('error') }}</div>
                                            @endif
                                            
                                            <form wire:submit.prevent="import" enctype="multipart/form-data">
                                                <div class="mb-3">
                                                    <label for="file" class="form-label">Upload CSV File</label>
                                                    <input type="file" wire:model="file" class="form-control">
                                                    @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-file-csv me-1"></i> Import
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-body pb-0">
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
                                        <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">
                                           
                                        </th> -->
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">
                                        Expense Date
                                        </th>
                                        
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">
                                            Transaction ID
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">
                                          Amount
                                        </th>
                                        <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">
                                          Status
                                        </th> -->
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">
                                           Action
                                        </th>
                                        <th class="text-secondary opacity-10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                            <tr>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">aa</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">aa</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">aa</p>
                                                </td>
                                                <td class="align-middle">
                                                    <a href="" class="btn btn-outline-info custom-btn-sm mb-0" data-toggle="tooltip" data-original-title="Edit user" title="Edit Customer">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                </tbody>

                            </table>
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
    <script>
        window.addEventListener('close-import-modal', event => {
            var importModal = document.getElementById('importModal');
            var modal = bootstrap.Modal.getInstance(importModal);
            modal.hide();
        });
    </script>
    