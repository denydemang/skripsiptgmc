<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CredentialController;
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

Route::get('/tesbranch', function() {
    return 'gg';
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




