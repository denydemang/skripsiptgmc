<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\COAController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UpahController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
use App\Models\Category;
use App\Models\COA;
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
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin', 'dashboard')->name('dashboard');
    });
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/admin/projecttype', 'getViewTypeProject')->name('admin.projecttype');
        Route::get('/admin/project', 'getViewProject')->name('admin.project');
        // View
        Route::get('/admin/projecttype', 'getViewTypeProject')->name('admin.projecttype');
        Route::get('/admin/project', 'getViewProject')->name('admin.project');
        Route::get('/admin/project/add', 'getViewProjectManage')->name('admin.addProjectView');
        Route::get('/admin/project/edit/{id?}', 'getViewProjectManage')->name('admin.editProjectView');
        Route::get('/admin/projectrecapitulation', 'projectrecapview')->name('admin.projectrecapview');
        Route::get('/admin/projectrealisation', 'projectrealisationview')->name('admin.projectrealisationview');
        Route::get('/admin/projectrealisation/finish/{id}', 'projectrealisationfinishview')->name('admin.projectrealisationfinishview');

        // CRUD
        Route::post('/admin/projecttype/getdata', 'getDataTypeProject')->name('admin.getDataProjectType');
        Route::post('/admin/projecttype/getSearchtable', 'getSearchtable')->name('admin.getSearchtable');
        Route::post('/admin/project/getdata', 'getDataProject')->name('admin.getDataProject');
        Route::post('/admin/projectrealisation/getdata', 'getDataProjectRealisation')->name('admin.getDataProjectRealisation');
        Route::post('/admin/projectrealisation/finish/{id}', 'finishproject')->name('admin.finishproject');
        Route::post('/admin/projecttype/update', 'updateProjectType')->name('admin.updateDataProjectType');
        Route::post('/admin/projecttype/add', 'addProjectType')->name('admin.addDataProjectType');
        Route::get('/admin/projecttype/delete/{id}', 'deleteProjectType')->name('admin.deleteDataProjectType');
        Route::get('/admin/project/delete/{id}', 'deleteProject')->name('admin.deleteDataProject');
        Route::post('/admin/project/add', 'addProject')->name('admin.addprojects');
        Route::post('/admin/project/edit/{id}', 'editProject')->name('admin.editproject');
        Route::get('/admin/projecttype/getDataRaw/{id}', 'getDataTypeProjectRaw')->name('admin.getDataTypeProjectRaw');
        Route::post('/admin/project/detail/getDataRaw/{id}', 'getDataDetailProjectRaw')->name('admin.getDataDetailProjectRaw');
        Route::post('/admin/project/start/{id}', 'startProject')->name('admin.startProject');

        // Print
        Route::get('/admin/project/printjournal/{code}', 'printjournal')->name('admin.printjournal');
        Route::get('/admin/project/printproject/{code}', 'printproject')->name('admin.printproject');
        Route::get('/admin/project/printprojectrecap', 'printprojectrecap')->name('admin.printprojectrecap');
    });
    // Route::controller(CustomerController::class)->group(function () {

    //     Route::get("admin/customer/getForModal", 'getDataCustomerForModal')->name('admin.CustomerGetForModal');
    // });
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
    Route::controller(SupplierController::class)->group(function () {
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

    // -----------------    Request Ajax

    Route::get('/admin/coa', function () {
        if (request()->ajax()) {
            $coaAll = COA::all(['code', 'name', 'type', 'level']);
            return json_encode($coaAll);
        }
    })->name('admin.coa');
    Route::get('/admin/JSONunit', function () {
        if (request()->ajax()) {
            $All = Unit::all(['code', 'name']);
            return json_encode($All);
        }
    })->name('admin.JSONunit');
    Route::get('/admin/JSONcategory', function () {
        if (request()->ajax()) {
            $All = Category::all(['code', 'name', 'coa_code']);
            return json_encode($All);
        }
    })->name('admin.JSONcategory');
    Route::get('/admin/JSONrole', function () {
        if (request()->ajax()) {
            $All = Role::all(['id', 'name']);
            return json_encode($All);
        }
    })->name('admin.JSONrole');

    // -----------------------------------------


    // Purchase Request
    // -----------------------------------------
    Route::controller(PurchaseRequestController::class)->group(function(){

        // GET VIEW
        Route::get('/admin/purchaserequest', 'getViewPR')->name('admin.pr');
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
    // ------------------------------------------

        // Inventory
    // -----------------------------------------
    Route::controller(StockController::class)->group(function(){

        // GET VIEW
        Route::get('/admin/inventoryin', 'getViewInventoryIN')->name('admin.iin');
        Route::get('/admin/inventoryout', 'getViewInventoryOUT')->name('admin.iout');
        Route::get('/admin/inventoryin/addbeginningbalance', 'getViewInventoryInManage')->name('admin.addbeginningview');

        // CRUD
        Route::post('/admin/inventoryin/gettable', 'getTableInventoryIn')->name('admin.tableiin');
        Route::post('/admin/inventoryout/gettable', 'getTableInventoryOut')->name('admin.tableout');
        
        // Print
        Route::get('/admin/inventoryin/printiin', 'printIIN')->name('admin.printIIN');
        Route::get('/admin/inventoryin/printiout', 'printIOUT')->name('admin.printIOUT');

    });
    // ------------------------------------------

});

