<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
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

Route::get('/admin/master/unit', function() {
    if (request()->ajax()) {
        $users = User::query();

        return DataTables::of($users)
            ->make();
    }

    return view('admin.master.unit',  [
        'title' => 'Welcome To Dashboard',
        'users' => Auth::user(),
        'sessionRoute' =>  'unit'
    ]);
});

Route::controller(CredentialController::class)->group(function(){
    Route::get('/', 'getLoginView')->name('getloginpage')->middleware(GuestMiddleware::class);
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');

});

Route::middleware(AuthMiddleware::class)->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin', 'dashboard')->name('dashboard');
    });  
    Route::controller(ProjectController::class)->group(function(){
        Route::get('/admin/projecttype', 'getViewTypeProject')->name('admin.projecttype');  
        Route::get('/admin/project', 'getViewProject')->name('admin.project');  
        Route::post('/admin/projecttype/getdata', 'getDataTypeProject')->name('admin.getDataProjectType');
        Route::post('/admin/project/getdata', 'getDataProject')->name('admin.getDataProject');
        Route::post('/admin/projecttype/update', 'updateProjectType')->name('admin.updateDataProjectType');
        Route::post('/admin/projecttype/add', 'addProjectType')->name('admin.addDataProjectType');
        Route::get('/admin/projecttype/delete/{id}', 'deleteProjectType')->name('admin.deleteDataProjectType');
        Route::get('/admin/projecttype/getDataRaw/{id}', 'getDataTypeProjectRaw')->name('admin.getDataTypeProjectRaw');
    });  
});

// -----------------    user
Route::middleware(AuthMiddleware::class)->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin', 'dashboard')->name('dashboard');
    });  
    Route::controller(UserController::class)->group(function(){
        Route::get('/admin/users', 'getViewUsers')->name('admin.users');  
        // Route::get('/admin/project', 'getViewProject')->name('admin.project');  
        Route::post('/admin/users/getdata', 'getDataUsers')->name('admin.getDataUsers');
        // Route::post('/admin/project/getdata', 'getDataProject')->name('admin.getDataProject');
        Route::post('/admin/users/update', 'updateUser')->name('admin.updateDataUser');
        Route::post('/admin/users/add', 'addUsers')->name('admin.addDataUsers');
        Route::get('/admin/users/delete/{username}', 'deleteUser')->name('admin.deleteDataUser');
        Route::get('/admin/users/edit/{username}', 'editDataUsers')->name('admin.editUsers');
    });  
});

// -----------------    unit
Route::middleware(AuthMiddleware::class)->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin', 'dashboard')->name('dashboard');
    });  
    Route::controller(UnitController::class)->group(function(){
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
});

// -----------------    item
Route::middleware(AuthMiddleware::class)->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin', 'dashboard')->name('dashboard');
    });  
    Route::controller(ItemController::class)->group(function(){
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
});

// -----------------    category
Route::middleware(AuthMiddleware::class)->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin', 'dashboard')->name('dashboard');
    });  
    Route::controller(CategoryController::class)->group(function(){
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
});

// -----------------    customer
Route::middleware(AuthMiddleware::class)->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin', 'dashboard')->name('dashboard');
    });  
    Route::controller(CustomerController::class)->group(function(){
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
});

// -----------------    supplier
Route::middleware(AuthMiddleware::class)->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin', 'dashboard')->name('dashboard');
    });  
    Route::controller(SupplierController::class)->group(function(){
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
});

// -----------------    role
Route::middleware(AuthMiddleware::class)->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin', 'dashboard')->name('dashboard');
    });  
    Route::controller(RoleController::class)->group(function(){
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
});



