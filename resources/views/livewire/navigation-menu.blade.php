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
                <a class="nav-link text-white {{ Route::currentRouteName() == $module['route'] ? ' active bg-gradient-primary' : '' }}"
                    href="{{ route($module['route']) }}">
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
            <li class="nav-item">
                <a class="nav-link text-white {{ in_array(Route::currentRouteName(), ['admin.categories', 'admin.subcategories', 'product.view','product.add','product.update']) ? 'active bg-gradient-primary' : '' }}"
                    href="#productManagementSubmenu" data-bs-toggle="collapse" aria-expanded="{{ in_array(Route::currentRouteName(), ['admin.categories', 'admin.subcategories', 'product.view','product.add','product.update']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">store</i>
                    </div>
                    <span class="nav-link-text ms-1">Product Management</span>
                </a>
            </li>
        
            <!-- Submenu -->
            <ul id="productManagementSubmenu" class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['admin.categories', 'admin.subcategories', 'product.view','product.add','product.update']) ? 'show' : '' }}">  
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'product.view' ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('product.view')}}">
                         Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'admin.categories' ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('admin.categories')}}">
                        Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'admin.subcategories' ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('admin.subcategories')}}">
                        Sub Categories
                    </a>
                </li>
            </ul>
            <li class="nav-item">
                <a class="nav-link text-white {{ in_array(Route::currentRouteName(), ['staff.designation']) ? 'active bg-gradient-primary' : '' }}"
                    href="#StaffManagementSubmenu" data-bs-toggle="collapse" aria-expanded="{{ in_array(Route::currentRouteName(), ['staff.designation']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Staff Management</span>
                </a>
            </li>
        
            <!-- Submenu -->
            <ul id="StaffManagementSubmenu" class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['staff.designation']) ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'staff.designation' ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('staff.designation')}}">
                        Designation
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'admin.subcategories' ? 'active bg-gradient-primary' : '' }}"
                        href="{{route('admin.subcategories')}}">
                        Sub Categories
                    </a>
                </li> --}}
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
