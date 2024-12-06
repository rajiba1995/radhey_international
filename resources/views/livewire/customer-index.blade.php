
    <!-- Navbar -->
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
            <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 active " data-bs-toggle="tab" href="javascript:;"
                            role="tab" aria-selected="true">
                            <i class="material-icons text-lg position-relative">home</i>
                            <span class="ms-1">App</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" href="javascript:;"
                            role="tab" aria-selected="false">
                            <i class="material-icons text-lg position-relative">email</i>
                            <span class="ms-1">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" href="javascript:;"
                            role="tab" aria-selected="false">
                            <i class="material-icons text-lg position-relative">settings</i>
                            <span class="ms-1">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.user-address-form') }}" class="btn btn-primary">Add Customer</a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-body px-0 pb-2">
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
                                            Company Name
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Email
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Phone
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            WhatsApp Number
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            GST Number
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
                                                    <div class="d-flex px-2 py-1">
                                                        <!-- <div>
                                                            <img src="{{ asset('assets/img/team-2.jpg') }}" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                        </div> -->
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $user->company_name }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $user->phone }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $user->whatsapp_no }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $user->gst_number }}</p>
                                                </td>
                                                <td class="align-middle">
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
