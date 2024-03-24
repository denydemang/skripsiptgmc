<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CredentialController extends Controller
{
    public function getloginview(){

        return response()->view("login", ['title' => 'PT GMJ Login Page']);
    }
    public function login(LoginRequest $request){

        $dataValidated = $request->validated();


        $isAuthorised = Auth::attempt([
            "username" => $dataValidated["username"],
            "password" =>  $dataValidated["password"]
    ], true); 
        
        if  ($isAuthorised){
            Session::regenerate();
            return redirect()->to("admin");
        } else {
            return redirect()->back()->withErrors(['msg' => 'Invalid Username/Password'])->withInput();
        }
        

    }
    public function logout(){
        Auth::logout();
        Session::flush();
        return redirect()->to("/");
    }


}
