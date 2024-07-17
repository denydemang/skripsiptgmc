<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HPPController extends Controller
{
    public function getViewHPP(Request $request){
        $supplyData = [
            'title' =>"HPP",
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()
            ];

        return response()->view("admin.accounting.hpp",$supplyData);
    }
    public function printhpp(Request $request){
        
            $data = $request->all();
    
            $print = new PrintController();
            return $print->printhpp($data['startDate'], $data['endDate']);
    }
}
