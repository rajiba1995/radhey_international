
    <!-- Navbar -->
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.user-address-form') }}" class="btn btn-primary">Add Customer</a>
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
                                                    <img src="{{asset('storage/'.$user->profile_image)}}" alt="profile-image" width="85px">
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
                                                    <p class="text-xs font-weight-bold mb-0">{{ $user->company_name }}</p>
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('admin.customers.details', ['id' => $user->id]) }}" class="btn btn-info btn-sm">View Details</a>
                                                    <a href="{{ route('admin.customers.edit', ['id' => $user->id]) }}" class="btn btn-outline-info btn-sm custom-btn-sm" data-toggle="tooltip" data-original-title="Edit user">
                                                        Edit
                                                    </a>
                                                    <button wire:click="deleteCustomer({{ $user->id }})" class="btn btn-outline-danger btn-sm custom-btn-sm">Delete</button>
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
