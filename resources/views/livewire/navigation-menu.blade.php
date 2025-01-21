<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href=" {{ route('dashboard') }} ">
                <img src="{{ asset('assets') }}/img/logo.png" class="navbar-brand-img h-100" alt="main_logo">
                {{-- <span class="ms-2 font-weight-bold text-white">Radhey International</span> --}}
            </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            {{-- <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Laravel examples</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'user-profile' ? ' active bg-gradient-primary' : '' }} "
                    href="{{ route('user-profile') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1.2rem;" class="fas fa-user-circle ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1">User Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'user-management' ? ' active bg-gradient-primary' : '' }} "
                    href="{{ route('user-management') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1rem;" class="fas fa-lg fa-list-ul ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1">User Management</span>
                </a>
            </li> --}}
            {{-- <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Pages</h6>
            </li> --}}
            @foreach($modules as $module)
            <li class="nav-item">
                <a class="nav-link text-white {{ in_array(Route::currentRouteName(), $module['route']) ? 'active bg-gradient-primary' : '' }}"
                href="{{ isset($module['route'][0]) ? route($module['route'][0]) : '#' }}"> <!-- Default to the first route -->
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">{{ $module['icon'] }}</i>
                    </div>
                    <span class="nav-link-text ms-1">{{ $module['name'] }}</span>
                </a>
            </li>
        @endforeach
            {{-- <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'tables' ? ' active bg-gradient-secondary' : '' }} "
                    href="{{ route('tables') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">table_view</i>
                    </div>
                    <span class="nav-link-text ms-1">Tables</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'billing' ? ' active bg-gradient-primary' : '' }}  "
                    href="{{ route('billing') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt_long</i>
                    </div>
                    <span class="nav-link-text ms-1">Billing</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'virtual-reality' ? ' active bg-gradient-primary' : '' }}  "
                    href="{{ route('virtual-reality') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">view_in_ar</i>
                    </div>
                    <span class="nav-link-text ms-1">Virtual Reality</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'rtl' ? ' active bg-gradient-primary' : '' }}  "
                    href="{{ route('rtl') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">format_textdirection_r_to_l</i>
                    </div>
                    <span class="nav-link-text ms-1">RTL</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'notifications' ? ' active bg-gradient-primary' : '' }}  "
                    href="{{ route('notifications') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">notifications</i>
                    </div>
                    <span class="nav-link-text ms-1">Notifications</span>
                </a>
            </li> --}}
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Master Modules</h6>
            </li>
            {{-- Purchase Order --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('admin/purchase-order*') || in_array(Route::currentRouteName(), ['purchase_order.index']) ? 'active bg-gradient-primary' : '' }}"
                    href="#productManagementSubmenu" data-bs-toggle="collapse" aria-expanded="{{ in_array(Route::currentRouteName(), ['purchase_order.index']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt</i>
                    </div>
                    <span class="nav-link-text ms-1">Purchase Order</span>
                </a>
            </li>

            {{-- Product Management --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('admin/products*') || in_array(Route::currentRouteName(), ['product.view', 'product.gallery', 'product.add', 'product.update', 'admin.categories', 'admin.subcategories', 'measurements.index', 'product.fabrics','admin.collections.index']) ? 'active bg-gradient-primary' : '' }}"
                    href="#productManagementSubmenu" data-bs-toggle="collapse" aria-expanded="{{ in_array(Route::currentRouteName(), ['product.view','product.gallery','product.add','product.update','admin.categories','admin.subcategories','measurements.index','product.gallery','product.fabrics','admin.collections.index']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">category</i>
                    </div>
                    <span class="nav-link-text ms-1">Product Management</span>
                </a>
            </li>
        
            <!-- Submenu -->
            <ul id="productManagementSubmenu" class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['product.view', 'product.gallery', 'product.add', 'product.update', 'admin.categories', 'admin.subcategories', 'measurements.index', 'product.fabrics','admin.collections.index','admin.fabrics.index']) ? 'show' : '' }}">  
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('admin/products/collections') ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('admin.collections.index')}}">
                         Collections
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('admin/products/categories*') ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('admin.categories')}}">
                        Categories
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'admin.fabrics.index' ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('admin.fabrics.index')}}">
                        Fabrics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'product.view' ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('product.view')}}">
                         Products
                    </a>
                </li>
                
               
                {{-- <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'admin.subcategories' || Route::currentRouteName() == 'measurements.index' ? 'active bg-gradient-primary' : '' }}"
                        href="{{ route('admin.subcategories') }}">
                        Sub Categories
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'admin.fabrics.index' ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('admin.fabrics.index')}}">
                        Fabrics
                    </a>
                </li> --}}
                
            </ul>
            <li class="nav-item">
                <a class="nav-link text-white {{ in_array(Route::currentRouteName(), ['staff.designation','staff.index','staff.add']) ? 'active bg-gradient-primary' : '' }}"
                    href="#StaffManagementSubmenu" data-bs-toggle="collapse" aria-expanded="{{ in_array(Route::currentRouteName(), ['staff.designation','staff.index','staff.add']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">assignment_ind</i>
                    </div>
                    <span class="nav-link-text ms-1">Staff Management</span>
                </a>
            </li>
        
            <!-- Submenu -->
            <ul id="StaffManagementSubmenu" class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['staff.designation','staff.index','staff.add','staff.update','staff.view','staff.task','staff.task.add','staff.cities.add','salesman.index']) ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'staff.designation' ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('staff.designation')}}">
                        Designation
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ in_array(Route::currentRouteName(), ['staff.index','staff.add','staff.update','staff.view','staff.task','staff.task.add','staff.cities.add']) ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('staff.index')}}">
                        Staff
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'salesman.index' ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('salesman.index')}}">
                        Staff Bill Book
                    </a>
                </li>
            </ul>
            {{-- Expense management --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ in_array(Route::currentRouteName(), ['expense.index']) ? 'active bg-gradient-primary' : '' }}"
                    href="#ExpenseManagementSubmenu" data-bs-toggle="collapse" aria-expanded="{{ in_array(Route::currentRouteName(), ['expense.index']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">attach_money</i>
                    </div>
                    <span class="nav-link-text ms-1">Expense Management</span>
                </a>
            </li>
            <ul id="ExpenseManagementSubmenu" class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['expense.index']) ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'expense.index' && request()->get('parent_id') == 1 ? 'active bg-gradient-primary' : '' }}"
                    href="{{ route('expense.index', ['parent_id' => 1]) }}">
                        Recurring 
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'expense.index' && request()->get('parent_id') == 2 ? 'active bg-gradient-primary' : '' }}"
                    href="{{ route('expense.index', ['parent_id' => 2]) }}">
                       Non Recurring 
                    </a>
                </li>
            </ul>
            {{-- Order Management --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('admin/orders*') ? 'active bg-gradient-primary' : '' }}"
                    href="#OrderManagementSubmenu" data-bs-toggle="collapse" aria-expanded="{{ Request::is('admin/orders*') ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">shopping_cart</i>
                    </div>
                    <span class="nav-link-text ms-1">Order Management</span>
                </a>
            </li>
        
            <!-- Submenu -->
            <ul id="OrderManagementSubmenu" class="collapse list-unstyled ms-4 {{ Request::is('admin/orders*') ? 'show' : '' }}">  
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('admin/orders') ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('admin.order.index')}}">
                         All Orders
                    </a>
                </li>
                <a class="nav-link text-white {{ Request::is('admin/orders/new') ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('admin.order.new')}}">
                         Place Order
                </a>
            </ul>
           
        </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
        <div class="mx-3">
            <a class="btn bg-gradient-secondary w-100" href="javascript:;">
                <livewire:auth.logout/>
            </a>
        </div>
    </div>
</aside>
