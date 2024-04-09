<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\COAController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UpahController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
use Illuminate\Support\Facades\Route;

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
        // View
        Route::get('/admin/projecttype', 'getViewTypeProject')->name('admin.projecttype');  
        Route::get('/admin/project', 'getViewProject')->name('admin.project');  
        Route::get('/admin/project/add', 'getViewProjectManage')->name('admin.addProjectView');  
        Route::get('/admin/project/edit/{id?}', 'getViewProjectManage')->name('admin.editProjectView');

        // CRUD
        Route::post('/admin/projecttype/getdata', 'getDataTypeProject')->name('admin.getDataProjectType');
        Route::post('/admin/projecttype/getSearchtable', 'getSearchtable')->name('admin.getSearchtable');
        Route::post('/admin/project/getdata', 'getDataProject')->name('admin.getDataProject');
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
        

    });  
    Route::controller(CustomerController::class)->group(function(){

        Route::get("admin/customer/getForModal", 'getDataCustomerForModal')->name('admin.CustomerGetForModal');

    });
    Route::controller(COAController::class)->group(function(){

        Route::get("admin/coa/gettablesearch", 'getCOATableSearch')->name('admin.getCOATableSearch');

    });
    Route::controller(ItemController::class)->group(function(){

        Route::get('admin/item/gettableitemsearch', 'getTableItemSearch')->name('admin.getTableItemSearch');
    });

    Route::controller(UpahController::class)->group(function(){

        Route::get('admin/upah/gettableupahsearch', 'getTableUpahSearch')->name('admin.getTableUpahSearch');
    });
    Route::controller(StockController::class)->group(function(){

        Route::get('admin/stocks/stockout/{id}', 'stockout')->name('admin.stockout');
    });

    Route::controller(FileController::class)->group(function(){

        Route::get('admin/download/{idfile}', 'downloadFile')->name('admin.download');
    });
    
});




