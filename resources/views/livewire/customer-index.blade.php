
    <!-- Navbar -->
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Customer List Title -->
            <h4 class="m-0">Customer List</h4> 
    
            <!-- Search Bar -->
            <div class="input-group w-50">
                <input type="text" class="form-control border" placeholder=" Search Customers" aria-label="Search" aria-describedby="button-search" style="height: fit-content">
                <button class="btn btn-outline-primary" type="button" id="button-search">
                    <i class="fas fa-search"></i>
                </button>
            </div>
    
            <!-- Add Customer Button -->
            <a href="{{ route('admin.user-address-form') }}" class="btn btn-primary btn-sm">
                <i class="material-icons text-white" style="font-size: 15px;">add</i>
                Add Customer
            </a>
        </div>
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                           Profile Image
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Name
                                        </th>
                                        {{-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Email
                                        </th> --}}
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Phone
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                           Company Name
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
                                    @foreach($users as $user)
                                        @if($user->email != 'admin@gmail.com') 
                                            <tr>
                                                <td>
                                                    <img src="{{asset($user->profile_image)}}" alt="profile-image" width="85px">
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <!-- <div>
                                                            <img src="{{ asset('assets/img/team-2.jpg') }}" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                        </div> -->
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $user->phone }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $user->company_name ?? 'N/A'}}</p>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input ms-auto" type="checkbox" wire:click="toggleStatus({{ $user->id }})" 
                                                        @if ($user->status)
                                                            checked
                                                        @endif>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('admin.customers.details', ['id' => $user->id]) }}" class="btn btn-outline-dark custom-btn-sm" data-toggle="tooltip" data-original-title="View Details" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.customers.edit', ['id' => $user->id]) }}" class="btn btn-outline-info custom-btn-sm" data-toggle="tooltip" data-original-title="Edit user" title="Edit Customer">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button wire:click="deleteCustomer({{ $user->id }})" class="btn btn-outline-danger custom-btn-sm" title="Delete Customer"><i class="fas fa-trash"></i></button>
                                                    <a href="" class="btn btn-outline-primary custom-btn-sm" data-toggle="tooltip" data-original-title="Place Order" title="Place Order">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </a>
                                                      <!-- Purchase History (Ledger) Button -->
                                                    <a href="" class="btn btn-outline-secondary custom-btn-sm" data-toggle="tooltip" data-original-title="Purchase History" title="Purchase History">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </a>

                                                    <!-- Add Payment Button -->
                                                    <a href="" class="btn btn-outline-success custom-btn-sm" data-toggle="tooltip" data-original-title="Add Payment" title="Add Payment">
                                                        <i class="fas fa-credit-card"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="mt-3">
                            <nav aria-label="Page navigation">
                                {{ $users->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
