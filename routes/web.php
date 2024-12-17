<?php

use App\Http\Livewire\Auth\ForgotPassword;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\AdminLogin;
use App\Http\Livewire\AdminDashboard;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\ResetPassword;
use App\Http\Livewire\Billing;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\ExampleLaravel\UserManagement;
use App\Http\Livewire\ExampleLaravel\UserProfile;
use App\Http\Livewire\Notifications;
use App\Http\Livewire\Profile;
use App\Http\Livewire\RTL;
use App\Http\Livewire\StaticSignIn;
use App\Http\Livewire\StaticSignUp;
use App\Http\Livewire\Tables;
use App\Http\Livewire\{VirtualReality,CustomerIndex};
use GuzzleHttp\Middleware;
use App\Http\Livewire\Product\{MasterProduct,AddProduct,UpdateProduct,MasterCategory,MasterSubCategory};
use App\Http\Livewire\Staff\{DesignationIndex,StaffIndex,StaffAdd,StaffUpdate,StaffView,StaffTask,StaffTaskAdd};
use App\Http\Livewire\Expense\{ExpenseIndex};
use App\Http\Livewire\UserAddressForm; 
use App\Http\Livewire\CustomerEdit; 
use App\Http\Livewire\CustomerDetails; 
use App\Http\Livewire\Supplier\SupplierIndex;
use App\Http\Livewire\Supplier\SupplierAdd;
use App\Http\Livewire\Supplier\SupplierEdit;
use App\Http\Livewire\Supplier\SupplierDetails;
use App\Http\Livewire\Measurement\MeasurementIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
    return redirect('admin/login');
});

Route::get('/sign-in', function(){
    return redirect('admin/login');
});

Route::get('forgot-password', ForgotPassword::class)->middleware('guest')->name('password.forgot');
Route::get('reset-password/{id}', ResetPassword::class)->middleware('signed')->name('reset-password');

// Route::get('sign-up', Register::class)->middleware('guest')->name('register');
// Route::get('sign-in', Login::class)->middleware('guest')->name('login');

// Route::get('user-profile', UserProfile::class)->middleware('auth')->name('user-profile');
// Route::get('user-management', UserManagement::class)->middleware('auth')->name('user-management');

// Route::group(['middleware' => 'auth'], function () {
//     Route::get('dashboard', Dashboard::class)->name('dashboard');
//     Route::get('billing', Billing::class)->name('billing');
//     Route::get('profile', Profile::class)->name('profile');
//     Route::get('tables', Tables::class)->name('tables');
//     Route::get('notifications', Notifications::class)->name("notifications");
//     Route::get('virtual-reality', VirtualReality::class)->name('virtual-reality');
//     Route::get('static-sign-in', StaticSignIn::class)->name('static-sign-in');
//     Route::get('static-sign-up', StaticSignUp::class)->name('static-sign-up');
//     Route::get('rtl', RTL::class)->name('rtl');
// });

Route::get('admin/login', AdminLogin::class)->middleware('guest')->name('admin.login');


Route::group(['prefix' => 'admin', 'middleware' => 'web'], function () {
    Route::get('/', function(){return redirect('admin/dashboard');});
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('billing', Billing::class)->name('billing');
    Route::get('profile', Profile::class)->name('profile');
    Route::get('tables', Tables::class)->name('tables');
    Route::get('notifications', Notifications::class)->name("notifications");
    Route::get('virtual-reality', VirtualReality::class)->name('virtual-reality');
    Route::get('static-sign-in', StaticSignIn::class)->name('static-sign-in');
    Route::get('static-sign-up', StaticSignUp::class)->name('static-sign-up');
    Route::get('rtl', RTL::class)->name('rtl');

    Route::get('/customers', CustomerIndex::class)->name('customers.index');
    
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', MasterProduct::class)->name('product.view');
        Route::get('/products/import', MasterProduct::class)->name('product.import');
        Route::get('/add/products', AddProduct::class)->name('product.add');
        Route::get('/update/products/{product_id}', UpdateProduct::class)->name('product.update');
        Route::get('/categories', MasterCategory::class)->name('admin.categories');
        Route::get('/subcategories', MasterSubCategory::class)->name('admin.subcategories');
        Route::get('/measurements/{subcategory}', MeasurementIndex::class)->name('measurements.index');
        Route::post('/measurements/update-positions', [MeasurementIndex::class, 'updatePositions'])->name('measurements.updatePositions');
    });

    Route::get('/designation',DesignationIndex::class)->name('staff.designation');
    
    // Staff
    Route::get('/staff',StaffIndex::class)->name('staff.index');
    Route::get('/staff/add',StaffAdd::class)->name('staff.add');
    Route::get('/staff/update/{staff_id}',StaffUpdate::class)->name('staff.update');
    Route::get('/staff/view/{staff_id}',StaffView::class)->name('staff.view');
    Route::get('/staff/task/{staff_id}',StaffTask::class)->name('staff.task');
    Route::get('/staff/task/add/{staff_id}',StaffTaskAdd::class)->name('staff.task.add');
    
    Route::get('/user-address-form', UserAddressForm::class)->name('admin.user-address-form');
    Route::get('/customers/{id}/edit', CustomerEdit::class)->name('admin.customers.edit');
    Route::get('/customers/{id}', CustomerDetails::class)->name('admin.customers.details');

    Route::get('/suppliers', SupplierIndex::class)->name('suppliers.index');
    Route::get('/suppliers/add', SupplierAdd::class)->name('suppliers.add');
    Route::get('/suppliers/edit/{id}', SupplierEdit::class)->name('suppliers.edit');
    Route::get('/suppliers/details/{id}', SupplierDetails::class)->name('suppliers.details');

    // Expense
    Route::get('/expense/{parent_id}', ExpenseIndex::class)->name('expense.index');
    
    

    // Route::get('/measurements/add', MeasurementAdd::class)->name('measurements.add');
    // Route::get('/measurements/edit/{id}', MeasurementEdit::class)->name('measurements.edit');
    // Route::get('/measurements/details/{id}', MeasurementDetails::class)->name('measurements.details');
});