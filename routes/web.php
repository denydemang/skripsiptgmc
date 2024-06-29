<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdvanceReceiptController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\CashBookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangeOfCapitalController;
use App\Http\Controllers\COAController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ProfitLossController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectRealisationController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TrialBalanceConttroller;
use App\Http\Controllers\UpahController;
use App\Http\Middleware\AdvancedReceiptMiddleware;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\BalanceSheetmiddleware;
use App\Http\Middleware\CapitalChangemiddleware;
use App\Http\Middleware\CashbookMiddleware;
use App\Http\Middleware\DashBoardMiddleware;
use App\Http\Middleware\GuestMiddleware;
use App\Http\Middleware\IINmiddleware;
use App\Http\Middleware\InvoiceMiddleware;
use App\Http\Middleware\IOUTMiddleware;
use App\Http\Middleware\JournalMiddleware;
use App\Http\Middleware\LedgerMiddleware;
use App\Http\Middleware\PaymentMiddleware;
use App\Http\Middleware\ProfitLossMiddleware;
use App\Http\Middleware\ProjectMiddleware;
use App\Http\Middleware\ProjectRealisationMiddleware;
use App\Http\Middleware\ProjectTypeMiddleware;
use App\Http\Middleware\PurchaseMiddleware;
use App\Http\Middleware\PurchaseRequestMiddleware;
use App\Http\Middleware\ReceiptMiddleware;
use App\Http\Middleware\StockCardMiddleware;
use App\Http\Middleware\StockReminderMiddleware;
use App\Http\Middleware\StocksMiddleware;
use App\Http\Middleware\SuppplierMiddleware;
use App\Http\Middleware\TrialBalanceMiddleware;
use App\Models\Advanced_Receipt;
use App\Models\CashBook;
use App\Models\Category;
use App\Models\COA;
use App\Models\ProjectRealisation;
use App\Models\Role;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::controller(CredentialController::class)->group(function () {
    Route::get('/', 'getLoginView')->name('getloginpage')->middleware(GuestMiddleware::class);
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware(AuthMiddleware::class)->group(function () {
    Route::middleware(DashBoardMiddleware::class)->group(function(){
        Route::controller(AdminController::class)->group(function () {
            Route::get('/admin', 'dashboard')->name('dashboard');
        });
    });


    Route::controller(ProjectTypeController::class)->group(function(){
        Route::middleware(ProjectTypeMiddleware::class)->group(function(){
            Route::get('/admin/projecttype', 'getViewTypeProject')->name('admin.projecttype');
            Route::get('/admin/projecttype/add', 'getViewManageTypeProject')->name('admin.addprojecttypeview');
            Route::get('/admin/projecttype/edit/{id}', 'getViewManageTypeProject')->name('admin.editprojecttypeview');
            Route::post('/admin/projecttype/getdata', 'getDataTypeProject')->name('admin.getDataProjectType');
            Route::post('/admin/projecttype/getSearchtable', 'getSearchtable')->name('admin.getSearchtable');
            Route::post('/admin/projecttype/update/{id}', 'updateProjectType')->name('admin.updateDataProjectType');
            Route::get('/admin/projecttype/delete/{id}', 'deleteProjectType')->name('admin.deleteDataProjectType');
            Route::post('/admin/projecttype/add', 'addProjectType')->name('admin.addDataProjectType');
            Route::get('/admin/projecttype/material/{projectypecode}', 'getdataforproyek')->name('admin.getdataforproyek');
            Route::get('/admin/projecttype/getDataRaw/{id}', 'getDataTypeProjectRaw')->name('admin.getDataTypeProjectRaw');

        });

    });

    Route::controller(ProjectController::class)->group(function () {

        Route::middleware(ProjectMiddleware::class)->group(function(){

            // View
            Route::get('/admin/project', 'getViewProject')->name('admin.project');
            Route::get('/admin/project/add', 'getViewProjectManage')->name('admin.addProjectView');
            Route::get('/admin/projectrecapitulation', 'projectrecapview')->name('admin.projectrecapview');
            // Route::get('/admin/projectrealisation', 'projectrealisationview')->name('admin.projectrealisationview');
            // Route::get('/admin/projectrealisation/finish/{id}', 'projectrealisationfinishview')->name('admin.projectrealisationfinishview');
            
            // CRUD
            Route::get('/admin/project/edit/{id?}', 'getViewProjectManage')->name('admin.editProjectView');
            Route::post('/admin/project/getdata', 'getDataProject')->name('admin.getDataProject');
            Route::get('/admin/project/getSearchtable', 'getDataSearch')->name('admin.getDataProjectSearch');
            // Route::post('/admin/projectrealisation/getdata', 'getDataProjectRealisation')->name('admin.getDataProjectRealisation');
            // Route::post('/admin/projectrealisation/finish/{id}', 'finishproject')->name('admin.finishproject');
            Route::get('/admin/project/delete/{id}', 'deleteProject')->name('admin.deleteDataProject');
            Route::post('/admin/project/add', 'addProject')->name('admin.addprojects');
            Route::post('/admin/project/edit/{id}', 'editProject')->name('admin.editproject');
            Route::post('/admin/project/detail/getDataRaw/{id}', 'getDataDetailProjectRaw')->name('admin.getDataDetailProjectRaw');
            Route::post('/admin/project/start/{id}', 'startProject')->name('admin.startProject');
    
            // Print
            Route::get('/admin/project/printjournal/{code}', 'printjournal')->name('admin.printjournal');
            Route::get('/admin/project/printproject/{code}', 'printproject')->name('admin.printproject');
            Route::get('/admin/project/printprojectrecap', 'printprojectrecap')->name('admin.printprojectrecap');
        });
        
    });



    // Route::controller(CustomerController::class)->group(function () {

    //     Route::get("admin/customer/getForModal", 'getDataCustomerForModal')->name('admin.CustomerGetForModal');
    // });

    Route::controller(ProjectRealisationController::class)->group(function(){

        Route::middleware(ProjectRealisationMiddleware::class)->group(function(){

                // GET VIEW
            Route::get('/admin/projectrealisation', 'getViewProjectRealisation')->name('admin.projectrealisationview');
            Route::get('/admin/projectrealisation/add', 'getViewProjectRealisationManage')->name('admin.addProjectrealisationview');
            Route::get('/admin/projectrealisation/edit/{id}', 'getViewProjectRealisationManage')->name('admin.editProjectrealisationview');
            // Route::get('/admin/invoice/edit/{id}', 'getViewInvoiceManage')->name('admin.editInvoiceView');
    
            // // // CRUD
            Route::get('/admin/projectrealisation/gettablesearch', 'getProjectRealisationSearchtable')->name('admin.getProjectRealisationSearchtable');
            Route::get('/admin/projectrealisation/getdetail/{id}', 'getDetailRealisation')->name('admin.getDetailRealisation');
            Route::get('/admin/projectrealisation/gettable', 'getProjectRealisationtable')->name('admin.getDataProjectRealisationTable');
            Route::get('/admin/projectrealisation/getdatabyproyek/{projectcode}', 'getProjectRealisationDataByProyek')->name('admin.getDataRealisasiByProyek');
            Route::get('/admin/projectrealisation/getmaterialproyek/{projectcode}', 'getMaterialProyek')->name('admin.getMaterialProyek');
            Route::get('/admin/projectrealisation/getupahproyek/{projectcode}', 'getUpahProyek')->name('admin.getUpahProyek');
            // // Route::post('/admin/cashbook/gettable1/{id}', 'getTableCashBook1')->name('admin.tablecashbook1');
            // // Route::post('/admin/cashbook/gettable2/{id}', 'getTableCashBook2')->name('admin.tablecashbook2');
            Route::post('/admin/projectrealisation/add', 'addRealisation')->name('admin.addRealisation');
            Route::post('/admin/projectrealisation/edit/{id}', 'editrealisation')->name('admin.editrealisation');
            Route::get('/admin/projectrealisation/delete/{id}', 'deleterealisation')->name('admin.deleterealisation');
            Route::get('/admin/projectrealisation/approve/{id}', 'approverealisation')->name('admin.approverealisation');
            // // Route::get('/admin/payment/getpurchase/{id}', 'getpurchaseforpayment')->name('admin.getpurchaseforpayment');
    
    
            // // Print
            Route::get('/admin/projectrealisation/detail/print/{id}', 'printdetailprojectrealisation')->name('admin.printdetailprojectrealisation');
            Route::get('/admin/projectrealisation/jurnal/print/{id}', 'printjurnalprojectrealisation')->name('admin.printjurnalprojectrealisation');
            // Route::get('/admin/invoice/recap/print', 'printrecapinvoice')->name('admin.printrecapinvoice');
        });
   
    });

    Route::controller(COAController::class)->group(function () {

        Route::get("admin/coalist", 'getCOAView')->name('admin.coalist');
        Route::get("admin/coa/gettreecoa", 'getTreeCOA')->name('admin.getTreeCOA');
        Route::get("admin/coa/gettablesearch", 'getCOATableSearch')->name('admin.getCOATableSearch');
        Route::post("admin/coa/add", 'addCoa')->name('admin.addCoa');
        Route::post("admin/coa/edit/{id}", 'editcoa')->name('admin.editcoa');
        Route::post("admin/coa/delete/{id}", 'deletecoa')->name('admin.deletecoa');
        Route::post("admin/coa/delete/sub/{id}", 'deletecoasub')->name('admin.deletecoasub');

    });
    // Route::controller(ItemController::class)->group(function () {

    //     Route::get('admin/item/gettableitemsearch', 'getTableItemSearch')->name('admin.getTableItemSearch');
    // });

    Route::controller(UpahController::class)->group(function () {

        Route::get('admin/upah/gettableupahsearch', 'getTableUpahSearch')->name('admin.getTableUpahSearch');
    });
    Route::controller(StockController::class)->group(function () {

        Route::get('admin/stocks/stockout/{id}', 'stockout')->name('admin.stockout');
    });

    Route::controller(FileController::class)->group(function () {

        Route::get('admin/download/{idfile}', 'downloadFile')->name('admin.download');
    });

    // -----------------    user
    Route::controller(UserController::class)->group(function () {
        Route::get('/admin/users', 'getViewUsers')->name('admin.users');
        // Route::get('/admin/project', 'getViewProject')->name('admin.project');
        Route::post('/admin/users/getdata', 'getDataUsers')->name('admin.getDataUsers');
        // Route::post('/admin/project/getdata', 'getDataProject')->name('admin.getDataProject');
        Route::post('/admin/users/update', 'updateUser')->name('admin.updateDataUser');
        Route::post('/admin/users/add', 'addUsers')->name('admin.addDataUsers');
        Route::get('/admin/users/delete/{username}', 'deleteUser')->name('admin.deleteDataUser');
        Route::get('/admin/users/edit/{username}', 'editDataUsers')->name('admin.editUsers');
    });

    // -----------------    unit
    Route::controller(UnitController::class)->group(function () {
        Route::resource('/admin/unit', UnitController::class)->names([
            'index' => 'r_unit.index',
            'create' => 'r_unit.create',
            'store' => 'r_unit.store',
            'show' => 'r_unit.show',
            'edit' => 'r_unit.edit',
            'update' => 'r_unit.update',
            'destroy' => 'r_unit.destroy',
        ]);
        Route::post('/admin/unit/getdata', 'getDataUnits')->name('admin.getUnits');
    });

    // -----------------    item
    Route::controller(ItemController::class)->group(function () {

        Route::get('admin/item/gettableitemsearch', 'getTableItemSearch')->name('admin.getTableItemSearch');


        Route::resource('/admin/item', ItemController::class)->names([
            'index' => 'r_item.index',
            'create' => 'r_item.create',
            'store' => 'r_item.store',
            'show' => 'r_item.show',
            'edit' => 'r_item.edit',
            'update' => 'r_item.update',
            'destroy' => 'r_item.destroy',
        ]);
        Route::post('/admin/item/getdata', 'getDataitems')->name('admin.getitems');
    });

    // -----------------    category
    Route::controller(CategoryController::class)->group(function () {
        Route::resource('/admin/category', CategoryController::class)->names([
            'index' => 'r_category.index',
            'create' => 'r_category.create',
            'store' => 'r_category.store',
            'show' => 'r_category.show',
            'edit' => 'r_category.edit',
            'update' => 'r_category.update',
            'destroy' => 'r_category.destroy',
        ]);
        Route::post('/admin/category/getdata', 'getDatacategorys')->name('admin.getcategorys');
    });


    // -----------------    customer
    Route::controller(CustomerController::class)->group(function () {

        Route::get("admin/customer/getForModal", 'getDataCustomerForModal')->name('admin.CustomerGetForModal');




        Route::resource('/admin/customer', CustomerController::class)->names([
            'index' => 'r_customer.index',
            'create' => 'r_customer.create',
            'store' => 'r_customer.store',
            'show' => 'r_customer.show',
            'edit' => 'r_customer.edit',
            'update' => 'r_customer.update',
            'destroy' => 'r_customer.destroy',
        ]);
        Route::post('/admin/customer/getdata', 'getDatacustomers')->name('admin.getcustomers');
    });


    // -----------------    supplier



    // Route::middleware(SuppplierMiddleware::class)->group(function(){
    
    // });


    Route::controller(SupplierController::class)->group(function () {

        
        Route::get("admin/supplier/getForModal", 'getDataSupplierForModal')->name('admin.SupplierGetForModal');
        
        Route::resource('/admin/supplier', SupplierController::class)->names([
                'index' => 'r_supplier.index',
                'create' => 'r_supplier.create',
                'store' => 'r_supplier.store',
                'show' => 'r_supplier.show',
                'edit' => 'r_supplier.edit',
                'update' => 'r_supplier.update',
                'destroy' => 'r_supplier.destroy',
        ]);
            Route::post('/admin/supplier/getdata', 'getDatasuppliers')->name('admin.getsuppliers');
    });


    // -----------------    role
    Route::controller(RoleController::class)->group(function () {
        Route::resource('/admin/role', RoleController::class)->names([
            'index' => 'r_role.index',
            'create' => 'r_role.create',
            'store' => 'r_role.store',
            'show' => 'r_role.show',
            'edit' => 'r_role.edit',
            'update' => 'r_role.update',
            'destroy' => 'r_role.destroy',
        ]);
        Route::post('/admin/role/getdata', 'getDataroles')->name('admin.getroles');
    });

    // -----------------    Upah
    Route::controller(UpahController::class)->group(function () {
        Route::resource('/admin/upah', UpahController::class)->names([
            'index' => 'r_upah.index',
            'create' => 'r_upah.create',
            'store' => 'r_upah.store',
            'show' => 'r_upah.show',
            'edit' => 'r_upah.edit',
            'update' => 'r_upah.update',
            'destroy' => 'r_upah.destroy',
        ]);
    });

    // -----------------    Request Ajax
    Route::get('/admin/JSONcoa', function () {
        if (request()->ajax()) {
            $coaAll = COA::where('description', 'detail')->get(['code', 'name', 'description']);
            // return json_encode($coaAll);
            ?>
            <option></option>
            <?php
            foreach ($coaAll as $ca) { ?>
                <option value="<?php echo $ca->code?>"><?php echo $ca->code.' - '.$ca->name?></option>
            <?php }
        }
    })->name('admin.JSONcoa');

    Route::get('/admin/JSONcoa1', function () {
        if (request()->ajax()) {
            $coaAll = COA::where('description', 'detail')->get(['code', 'name', 'description']);
            // return json_encode($coaAll);
            ?>
            <option></option>
            <?php
            foreach ($coaAll as $ca) { ?>
                <option value="<?php echo $ca->code.'-'.$ca->name?>"><?php echo $ca->code.' - '.$ca->name?></option>
            <?php }
        }
    })->name('admin.JSONcoa1');
    Route::get('/admin/JSONunit', function () {
        if (request()->ajax()) {
            $unitAll = Unit::all(['code', 'name']);
            ?>
            <option></option>
            <?php
            foreach ($unitAll as $ua) { ?>
                <option value="<?php echo $ua->code?>"><?php echo $ua->code.' - '.$ua->name?></option>
            <?php }
        }
    })->name('admin.JSONunit');
    Route::get('/admin/JSONcategory', function () {
        if (request()->ajax()) {
            $categoryAll = Category::all(['code', 'name', 'coa_code']);
            ?>
            <option></option>
            <?php
            foreach ($categoryAll as $ca) { ?>
                <option value="<?php echo $ca->code?>"><?php echo $ca->code.' - '.$ca->name?></option>
            <?php }
        }
    })->name('admin.JSONcategory');
    Route::get('/admin/JSONrole', function () {
        if (request()->ajax()) {
            $All = Role::all(['id', 'name']);
            return json_encode($All);
        }
    })->name('admin.JSONrole');

    // -----------------------------------------



    // Inventory
    // Purchase Request
    // -----------------------------------------
    Route::controller(PurchaseRequestController::class)->group(function(){

        Route::middleware(PurchaseRequestMiddleware::class)->group(function(){
      // GET VIEW
            Route::get('/admin/purchaserequest', 'getViewPR')->name('admin.pr');
            Route::get("admin/purchaserequest/getForModal", 'getDataPRForModal')->name('admin.PRGetForModal');
            Route::get('/admin/purchaserequest/add' ,'getViewPRManage')->name('admin.addprview');
            Route::get('/admin/purchaserequest/edit/{code}' ,'getViewPRManage')->name('admin.editprview');


            // CRUD
            Route::post('/admin/purchaserequest/add' ,'addPR')->name('admin.addpr');
            Route::post('/admin/purchaserequest/edit/{id}' ,'editPR')->name('admin.editpr');
            Route::post('/admin/purchaserequest/gettable', 'getTablePR')->name('admin.getprtable');
            Route::get('/admin/purchaserequest/delete/{id}', 'deletePR')->name('admin.deletePR');
            Route::get('/admin/purchaserequest/approve/{id}', 'approvePR')->name('admin.approvePR');
            Route::post('/admin/purchaserequest/detail/{id}', 'detailPR')->name('admin.detailPR');

            // PRINT
            Route::get('/admin/purchaserequest/print/{id}', 'printPR')->name('admin.printPR');
        });
    
    });
    // Stock
    Route::controller(StockController::class)->group(function(){


        Route::middleware(IINmiddleware::class)->group(function(){
            
            Route::get('/admin/inventoryin', 'getViewInventoryIN')->name('admin.iin');
            Route::get('/admin/inventoryin/printiin', 'printIIN')->name('admin.printIIN');
            Route::post('/admin/inventoryin/gettable', 'getTableInventoryIn')->name('admin.tableiin');
        });
        
        
        Route::middleware(IOUTMiddleware::class)->group(function(){
            Route::get('/admin/inventoryout', 'getViewInventoryOUT')->name('admin.iout');
            Route::get('/admin/inventoryout/printiout', 'printIOUT')->name('admin.printIOUT');
            Route::post('/admin/inventoryout/gettable', 'getTableInventoryOut')->name('admin.tableout');
        });


        Route::middleware(StocksMiddleware::class)->group(function(){
            Route::get('/admin/stocks', 'getViewStocks')->name('admin.stocks');
            Route::post('/admin/stocks/gettable', 'getTableStocks')->name('admin.tablestocks');
            Route::get('/admin/stocks/printstocks', 'printstock')->name('admin.printstock');
        });

        Route::middleware(StockReminderMiddleware::class)->group(function(){

            Route::get('/admin/stockreminder', 'getViewStockReminder')->name('admin.stockreminder');
            Route::post('/admin/stockreminder/gettable', 'getTableStockReminder')->name('admin.tablestockreminder');
            Route::get('/admin/stocks/printstockreminder', 'printstockreminder')->name('admin.printstockreminder');
        });


        Route::middleware(StockCardMiddleware::class)->group(function(){

            Route::get('/admin/stockcard', 'getViewStockCard')->name('admin.stockcard');
            Route::get('/admin/stockscard/print', 'printstockcard')->name('admin.printstockcard');
            Route::post('/admin/stockcard/gettable', 'getTableStockCard')->name('admin.tablestockcard');
        });

    });

    Route::controller(PurchaseController::class)->group(function(){

        Route::middleware(PurchaseMiddleware::class)->group(function(){


        // GET VIEW
        Route::get('/admin/purchase', 'getViewPurchase')->name('admin.purchase');
        Route::get('/admin/purchase/add', 'getViewPurchaseManage')->name('admin.addPurchaseView');
        Route::get('/admin/purchase/edit/{code}', 'getViewPurchaseManage')->name('admin.editPurchaseView');

         // CRUD
        Route::post('/admin/purchase/gettable', 'getTablePurchase')->name('admin.tablepurchase');
        Route::post('/admin/purchase/add', 'addPurchase')->name('admin.addpurchase');
        Route::post('/admin/purchase/edit/{id}', 'editPurchase')->name('admin.editpurchase');
        Route::get('/admin/purchase/approve/{id}', 'approvepurchase')->name('admin.approvepurchase');
        Route::get('/admin/purchase/delete/{id}', 'deletepurchase')->name('admin.deletepurchase');
        Route::post('/admin/purchase/detail/{id}', 'detailpurchase')->name('admin.detailpurchase');
        
        // Print
        Route::get('/admin/purchase/detail/print/{id}', 'printdetailpurchase')->name('admin.printdetailpurchase');
        Route::get('/admin/purchase/jurnal/print/{id}', 'printjurnalpurchase')->name('admin.printjurnalpurchase');
        Route::get('/admin/purchase/recap/print', 'printrecappurchase')->name('admin.printrecappurchase');
        
        });
    });

    Route::controller(PaymentController::class)->group(function(){

        Route::middleware(PaymentMiddleware::class)->group(function(){
        // GET VIEW
        Route::get('/admin/payment', 'getViewPayment')->name('admin.payment');
        Route::get('/admin/payment/add', 'getViewPaymentManage')->name('admin.addPaymentView');
        Route::get('/admin/payment/edit/{id}', 'getViewPaymentManage')->name('admin.editPaymentView');

        // CRUD
        Route::post('/admin/payment/gettable', 'getTablePayment')->name('admin.tablepayment');
        Route::post('/admin/payment/add', 'addPayment')->name('admin.addpayment');
        Route::post('/admin/payment/edit/{id}', 'editPayment')->name('admin.editpayment');
        Route::get('/admin/payment/delete/{id}', 'deletepayment')->name('admin.deletepayment');
        Route::get('/admin/payment/approve/{id}', 'approvepayment')->name('admin.approvepayment');
        Route::get('/admin/payment/getpurchase/{id}', 'getpurchaseforpayment')->name('admin.getpurchaseforpayment');


        // Print
        Route::get('/admin/payment/detail/print/{id}', 'printdetailpayment')->name('admin.printdetailpayment');
        Route::get('/admin/payment/jurnal/print/{id}', 'printjurnalpayment')->name('admin.printjurnalpayment');
        Route::get('/admin/payment/recap/print', 'printrecappayment')->name('admin.printrecappayment');


        });

    });

    Route::controller(CashBookController::class)->group(function(){

        Route::middleware(CashbookMiddleware::class)->group(function(){
        // GET VIEW
        Route::get('/admin/cashbook', 'getViewCashBook')->name('admin.cashbook');
        Route::get('/admin/cashbook/add', 'getViewCashbookManage')->name('admin.addCashbookView');
        Route::get('/admin/cashbook/edit/{id}', 'getViewCashbookManage')->name('admin.editCashbookView');

        // CRUD
        Route::post('/admin/cashbook/gettable', 'getTableCashBook')->name('admin.tablecashbook');
        Route::post('/admin/cashbook/gettable1/{id}', 'getTableCashBook1')->name('admin.tablecashbook1');
        Route::post('/admin/cashbook/gettable2/{id}', 'getTableCashBook2')->name('admin.tablecashbook2');
        Route::post('/admin/cashbook/add', 'addCashbook')->name('admin.addcashbook');
        Route::post('/admin/cashbook/edit/{id}', 'editCashbook')->name('admin.editcashbook');
        Route::get('/admin/cashbook/delete/{id}', 'deletecashbook')->name('admin.deletecashbook');
        Route::get('/admin/cashbook/approve/{id}', 'approvecashbook')->name('admin.approvecashbook');
        // Route::get('/admin/payment/getpurchase/{id}', 'getpurchaseforpayment')->name('admin.getpurchaseforpayment');


        // Print
        // Route::get('/admin/cashbook/detail/print/{id}', 'printdetailcashbook')->name('admin.printdetailcashbook');
        Route::get('/admin/cashbook/jurnal/print/{id}', 'printjurnalcashbook')->name('admin.printjurnalcashbook');
        // Route::get('/admin/payment/recap/print', 'printrecappayment')->name('admin.printrecappayment');

        });
    });

    Route::controller(AdvanceReceiptController::class)->group(function(){

        Route::middleware(AdvancedReceiptMiddleware::class)->group(function(){
        // GET VIEW
        Route::get('/admin/advancedreceipt', 'getViewAdvancedReceipt')->name('admin.advancedreceipt');
        Route::get('/admin/advancedreceipt/add', 'getViewAdvancedReceiptManage')->name('admin.addAdvancedReceiptView');
        Route::get('/admin/advancedreceipt/edit/{id}', 'getViewAdvancedReceiptManage')->name('admin.editAdvancedReceiptView');

        // // CRUD
        Route::post('/admin/advancedreceipt/gettable', 'getTableAR')->name('admin.tablear');
        // Route::post('/admin/cashbook/gettable1/{id}', 'getTableCashBook1')->name('admin.tablecashbook1');
        // Route::post('/admin/cashbook/gettable2/{id}', 'getTableCashBook2')->name('admin.tablecashbook2');
        Route::post('/admin/advancedreceipt/add', 'addAR')->name('admin.addAR');
        Route::post('/admin/advancedreceipt/edit/{id}', 'editAR')->name('admin.editAR');
        Route::get('/admin/advancedreceipt/delete/{id}', 'deletear')->name('admin.deletear');
        Route::get('/admin/advancedreceipt/approve/{id}', 'approvear')->name('admin.approvear');
        // Route::get('/admin/payment/getpurchase/{id}', 'getpurchaseforpayment')->name('admin.getpurchaseforpayment');


        // Print
        Route::get('/admin/advancedreceipt/detail/print/{id}', 'printdetailar')->name('admin.printdetailar');
        Route::get('/admin/advancedreceipt/jurnal/print/{id}', 'printjurnalar')->name('admin.printjurnalar');
        Route::get('/admin/advancedreceipt/recap/print', 'printrecapar')->name('admin.printrecapar');
        });
    });


    Route::controller(InvoiceController::class)->group(function(){


        Route::middleware(InvoiceMiddleware::class)->group(function(){
            // GET VIEW
            Route::get('/admin/invoice', 'getViewInvoice')->name('admin.invoice');
            Route::get('/admin/invoice/add', 'getViewInvoiceManage')->name('admin.addInvoiceView');
            Route::get('/admin/invoice/edit/{id}', 'getViewInvoiceManage')->name('admin.editInvoiceView');
    
            // // // CRUD
            Route::post('/admin/invoice/gettable', 'getTableInvoice')->name('admin.tableinvoice');
            // // Route::post('/admin/cashbook/gettable1/{id}', 'getTableCashBook1')->name('admin.tablecashbook1');
            // // Route::post('/admin/cashbook/gettable2/{id}', 'getTableCashBook2')->name('admin.tablecashbook2');
            Route::post('/admin/invoice/add', 'addinvoice')->name('admin.addinvoice');
            Route::post('/admin/invoice/edit/{id}', 'editinvoice')->name('admin.editinvoice');
            Route::get('/admin/invoice/delete/{id}', 'deleteinvoices')->name('admin.deleteinvoices');
            Route::get('/admin/invoice/approve/{id}', 'approveinvoices')->name('admin.approveinvoices');
            // // Route::get('/admin/payment/getpurchase/{id}', 'getpurchaseforpayment')->name('admin.getpurchaseforpayment');
    
    
            // // Print
            Route::get('/admin/invoice/detail/print/{id}', 'printdetailinvoice')->name('admin.printdetailinvoice');
            Route::get('/admin/invoice/jurnal/print/{id}', 'printjurnalinvoice')->name('admin.printjurnalinvoice');
            Route::get('/admin/invoice/recap/print', 'printrecapinvoice')->name('admin.printrecapinvoice');
        });

    });

    Route::controller(ReceiptController::class)->group(function(){

        Route::middleware(ReceiptMiddleware::class)->group(function(){

        // GET VIEW
        Route::get('/admin/receipt', 'getViewReceipt')->name('admin.receipt');
        Route::get('/admin/receipt/add', 'getViewReceiptManage')->name('admin.addReceiptView');
        Route::get('/admin/receipt/edit/{id}', 'getViewReceiptManage')->name('admin.editReceiptView');

        // CRUD
        Route::post('/admin/receipt/gettable', 'getTableReceipt')->name('admin.tablereceipt');
        Route::post('/admin/receipt/add', 'addreceipt')->name('admin.addreceipt');
        Route::post('/admin/receipt/edit/{id}', 'editreceipt')->name('admin.editreceipt');
        Route::get('/admin/receipt/delete/{id}', 'deletereceipt')->name('admin.deletereceipt');
        Route::get('/admin/receipt/approve/{id}', 'approvereceipt')->name('admin.approvereceipt');
        Route::get('/admin/receipt/getinvoice/{id}', 'getinvoiceforreceipt')->name('admin.getinvoiceforreceipt');
        Route::get('/admin/receipt/getbalancear/{id}', 'getbalancear')->name('admin.getbalancear');


        // // Print
        Route::get('/admin/receipt/detail/print/{id}', 'printdetailreceipt')->name('admin.printdetailreceipt');
        Route::get('/admin/receipt/jurnal/print/{id}', 'printjurnalreceipt')->name('admin.printjurnalreceipt');
        Route::get('/admin/receipt/recap/print', 'printrecapreceipt')->name('admin.printrecapreceipt');

        });
    });

    Route::controller(JournalController::class)->group(function(){

        Route::middleware(JournalMiddleware::class)->group(function(){
        // GET VIEW
        Route::get('/admin/journal', 'getViewJournal')->name('admin.journal');

        Route::get('/admin/journal/add', 'getViewJournalManage')->name('admin.addJournalView');
        Route::get('/admin/journal/edit/{id}', 'getViewJournalManage')->name('admin.editJournalView');

        // // CRUD
        Route::post('/admin/journal/gettable', 'getTableJournal')->name('admin.tablejournal');
        Route::post('/admin/journal/detail/{id}', 'detailjournal')->name('admin.detailjournal');
        Route::post('/admin/journal/add', 'addjournal')->name('admin.addjournal');
        Route::post('/admin/journal/edit/{id}', 'editJournal')->name('admin.editJournal');
        Route::get('/admin/journal/delete/{id}', 'deletejournal')->name('admin.deletejournal');
        Route::get('/admin/journal/posting/{id}', 'postingjournal')->name('admin.postingjournal');
        // Route::get('/admin/receipt/getinvoice/{id}', 'getinvoiceforreceipt')->name('admin.getinvoiceforreceipt');
        // Route::get('/admin/receipt/getbalancear/{id}', 'getbalancear')->name('admin.getbalancear');


        // // // Print
        // Route::get('/admin/receipt/detail/print/{id}', 'printdetailreceipt')->name('admin.printdetailreceipt');
        // Route::get('/admin/receipt/jurnal/print/{id}', 'printjurnalreceipt')->name('admin.printjurnalreceipt');
        Route::get('/admin/journal/recap/print', 'printjournalrecap')->name('admin.printjournalrecap');
        });


    });


    Route::controller(LedgerController::class)->group(function(){

        Route::middleware(LedgerMiddleware::class)->group(function(){
               //Get View
            Route::get('/admin/ledger', 'getViewLedger')->name('admin.ledger');

            // Print
            Route::get('/admin/ledger/print', 'printLedger')->name('admin.PrintLedger');
        });
     
    });


    Route::controller(TrialBalanceConttroller::class)->group(function(){
        Route::middleware(TrialBalanceMiddleware::class)->group(function(){
                //Get View
                Route::get('/admin/trialbalance', 'getViewTrialBalance')->name('admin.trialbalance');

                // Print
                Route::get('/admin/trialbalance/print', 'printtrialbalance')->name('admin.printtrialbalance');
        });
       
    });

    Route::controller(ProfitLossController::class)->group(function(){

        Route::middleware(ProfitLossMiddleware::class)->group(function(){
            //Get View
            Route::get('/admin/profitloss', 'getViewProfitLoss')->name('admin.profitloss');

            // Print
            Route::get('/admin/profitloss/print', 'printprofitloss')->name('admin.printprofitloss');
        });
      
    });

    Route::controller(BalanceSheetController::class)->group(function(){


        Route::middleware(BalanceSheetmiddleware::class)->group(function(){
            //Get View
            Route::get('/admin/balancesheet', 'getViewBalanceSheet')->name('admin.balancesheet');
    
            // Print
    
            Route::get('/admin/balancesheet/print', 'printbalancesheet')->name('admin.printbalancesheet');

        });
    });

    Route::controller(ChangeOfCapitalController::class)->group(function(){

        Route::middleware(CapitalChangemiddleware::class)->group(function(){
            //Get View
            Route::get('/admin/capitalchange', 'getViewCapitalChange')->name('admin.capitalchange');

            // Print
            Route::get('/admin/capitalchange/print', 'printcapitalchange')->name('admin.printcapitalchange');
        });

    });


    // ------------------------------------------

});

