<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request){

        $username = $request->post("username");
        $pw = $request->post("password");

        $isAuthorised = Auth::attempt([
            "username" => $username,
            "pw" =>  $pw 
        ], true); 

        if ($isAuthorised){
            return true;
        } else {
            return false;
        }
    }
}
