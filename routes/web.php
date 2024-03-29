<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\ProjectController;
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




