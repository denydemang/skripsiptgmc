<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard(Request $request){
        return response()
        ->view(
            "admin.dashboard", 
        [
            'title' => 'Welcome To Dashboard',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()
        
        ]);
    }
}
