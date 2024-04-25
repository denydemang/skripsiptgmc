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

    public function errorException($ex, $routeRedirectName , $code =""){
        if (!strpos($ex->getMessage(), "cannot delete or update a parent row")){
            return response()->redirectToRoute($routeRedirectName)->with("error","Unable To Delete $code Already Used By Another Transaction");
        } else {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute($routeRedirectName)->with("error", $ex->getMessage());
         }
    }
}
