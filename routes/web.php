<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CredentialController;
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
});




