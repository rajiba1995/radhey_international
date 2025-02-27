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
use App\Http\Livewire\Order\{OrderIndex, OrderNew, OrderInvoice,OrderEdit,OrderView,LedgerView,AddOrderSlip,InvoiceList,CancelOrderList,InvoiceEdit};
use App\Http\Livewire\Product\{MasterProduct,AddProduct,UpdateProduct,MasterCategory,MasterSubCategory,FabricIndex,CollectionIndex,GalleryIndex,MasterCatalogue};
use App\Http\Livewire\Staff\{DesignationIndex,StaffIndex,StaffAdd,StaffUpdate,StaffView,StaffTask,StaffTaskAdd,StaffCities,SalesmanBillingIndex,MasterBranch};
use App\Http\Livewire\Expense\{ExpenseIndex,DepotExpanse,DailyExpenses,DailyCollection};
use App\Http\Livewire\UserAddressForm; 
use App\Http\Livewire\CustomerEdit; 
use App\Http\Livewire\CustomerDetails; 
use App\Http\Livewire\Supplier\SupplierIndex;
use App\Http\Livewire\Supplier\SupplierAdd;
use App\Http\Livewire\Supplier\SupplierEdit;
use App\Http\Livewire\Supplier\SupplierDetails;
use App\Http\Livewire\Measurement\MeasurementIndex;
use App\Http\Livewire\Fabric\FabricsIndex;
use App\Http\Livewire\PurchaseOrder\{PurchaseOrderIndex,PurchaseOrderCreate,PurchaseOrderEdit,GenerateGrn,PurchaseOrderDetails,GeneratePdf};
use App\Http\Livewire\Stock\{StockIndex,UserLedger};
use App\Http\Livewire\Report\{UserLedgerReport};
use App\Http\Livewire\BusinessType\BusinessTypeIndex;
use App\Http\Livewire\Accounting\{AddPaymentReceipt,PaymentCollectionIndex,AddOpeningBalance,ListOpeningBalance};
// purchase Order pdf
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PurchaseOrder;


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

    
    
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', MasterProduct::class)->name('product.view');
        Route::get('/products/import', MasterProduct::class)->name('product.import');
        Route::get('/add/products', AddProduct::class)->name('product.add');
        Route::get('/update/products/{product_id}', UpdateProduct::class)->name('product.update');
        Route::get('/categories', MasterCategory::class)->name('admin.categories');
        Route::get('/subcategories', MasterSubCategory::class)->name('admin.subcategories');
        Route::get('/measurements/{product_id}', MeasurementIndex::class)->name('measurements.index');
        Route::get('/fabrics/{product_id}', FabricsIndex::class)->name('product_fabrics.index');
        Route::post('/measurements/update-positions', [MeasurementIndex::class, 'updatePositions'])->name('measurements.updatePositions');
        Route::get('/fabrics', FabricIndex::class)->name('admin.fabrics.index');

        Route::get('/collections', CollectionIndex::class)->name('admin.collections.index');
        Route::get('/gallery/{product_id}', GalleryIndex::class)->name('product.gallery');
        Route::get('/catalog', MasterCatalogue::class)->name('product.catalogue');

    });

    // Purchase Order
    Route::group(['prefix' => 'purchase-order'], function () {
       Route::get('/',PurchaseOrderIndex::class)->name('purchase_order.index');
       Route::get('/create',PurchaseOrderCreate::class)->name('purchase_order.create');
       Route::get('/edit/{purchase_order_id}',PurchaseOrderEdit::class)->name('purchase_order.edit');
       Route::get('/details/{purchase_order_id}',PurchaseOrderDetails::class)->name('purchase_order.details');
       Route::get('/generate-grn/{purchase_order_id}',GenerateGrn::class)->name('purchase_order.generate_grn');
       Route::get('/pdf/{purchase_order_id}',function($purchase_order_id){
            $purchaseOrder = PurchaseOrder::with('supplier', 'orderproducts')->findOrFail($purchase_order_id);
            $pdf = Pdf::loadView('livewire.purchase-order.generate-pdf', compact('purchaseOrder'));
            return $pdf->download('purchase_order_' . $purchase_order_id . '.pdf');
       })->name('purchase_order.generate_pdf');
    });

    // Business Type
    Route::group(['prefix'=> 'business-type'], function (){
       Route::get('/',BusinessTypeIndex::class)->name('business_type.index');
    });

    // Stock Report
    Route::group(['prefix' => 'stock'], function () {
       Route::get('/',StockIndex::class)->name('stock.index');
       Route::get('/user-ledger',UserLedger::class)->name('user.ledger');
    });

    Route::get('/branch',MasterBranch::class)->name('branch.index');
    Route::get('/designation',DesignationIndex::class)->name('staff.designation');
    
    // Staff
    Route::prefix('staff')->name('staff.')->group(function() {
        Route::get('/',StaffIndex::class)->name('index');
        Route::get('/add',StaffAdd::class)->name('add');
        Route::get('/update/{staff_id}',StaffUpdate::class)->name('update');
        Route::get('/view/{staff_id}',StaffView::class)->name('view');
        Route::get('/task/{staff_id}',StaffTask::class)->name('task');
        Route::get('/task/add/{staff_id}',StaffTaskAdd::class)->name('task.add');
        Route::get('cities/add/{salesman_id}',StaffCities::class)->name('cities.add');
    });
    
     // Salesman
    Route::prefix('salesman/bill-books')->name('salesman.')->group(function() {
        Route::get('/',SalesmanBillingIndex::class)->name('index');
    });
    
    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', CustomerIndex::class)->name('customers.index');
        Route::get('/add', UserAddressForm::class)->name('admin.user-address-form');
        Route::get('/{id}/edit', CustomerEdit::class)->name('admin.customers.edit');
        Route::get('/{id}', CustomerDetails::class)->name('admin.customers.details');
    });

    Route::prefix('suppliers')->name('suppliers.')->group(function() {
        Route::get('/', SupplierIndex::class)->name('index');
        Route::get('/add', SupplierAdd::class)->name('add');
        Route::get('/edit/{id}', SupplierEdit::class)->name('edit');
        Route::get('/details/{id}', SupplierDetails::class)->name('details');
    });
    // Expense
    Route::prefix('expense')->name('expense.')->group(function() {
        Route::get('/{parent_id}', ExpenseIndex::class)->name('index');

    });
    Route::prefix('accounting')->group(function() {
        Route::get('/collection-and-expenses', DepotExpanse::class)->name('admin.accounting.collection_and_expenses');
        Route::get('/daily/expenses', DailyExpenses::class)->name('admin.accounting.daily.expenses');
        Route::get('/payment-collection', PaymentCollectionIndex::class)->name('admin.accounting.payment_collection');
        Route::get('/add-payment-receipt/{payment_voucher_no?}', AddPaymentReceipt::class)->name('admin.accounting.add_payment_receipt');
        Route::get('/add-opening-balance', AddOpeningBalance::class)->name('admin.accounting.add_opening_balance');
        Route::get('/list-opening-balance', ListOpeningBalance::class)->name('admin.accounting.list_opening_balance');
    });

    Route::prefix('report')->group(function() {
        Route::get('/user-ledger', UserLedgerReport::class)->name('admin.report.user_ledger');
    });

    Route::prefix('daily-collection')->name('daily-collection.')->group(function() {
        // Route::get('/', DepotExpanse::class)->name('index');
        Route::get('/add', DailyCollection::class)->name('add');

    });

    
    // Route::get('/measurements/add', MeasurementAdd::class)->name('measurements.add');
    // Route::get('/measurements/edit/{id}', MeasurementEdit::class)->name('measurements.edit');
    // Route::get('/measurements/details/{id}', MeasurementDetails::class)->name('measurements.details');

    Route::group(['prefix' => 'orders'], function () {
        Route::get('/list/{customer_id?}', OrderIndex::class)->name('admin.order.index');
        Route::get('/add-slip/{id}', AddOrderSlip::class)->name('admin.order.add_order_slip');
        Route::get('/invoice/{id}', OrderInvoice::class)->name('admin.order.invoice');
        Route::get('/new', OrderNew::class)->name('admin.order.new');
        Route::get('/edit/{id}', OrderEdit::class)->name('admin.order.edit');
        Route::get('/view/{id}', OrderView::class)->name('admin.order.view');
        Route::get('/ledger/{id}', LedgerView::class)->name('admin.order.ledger.view');
        Route::get('/invoice', InvoiceList::class)->name('admin.order.invoice.index');
        Route::get('/cancel-order', CancelOrderList::class)->name('admin.order.cancel-order.index');
    });
});