<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeOfCapitalController extends AdminController
{
    public function getViewCapitalChange(Request $request){
        $supplyData = [
            'title' =>"Statement of Change in Capital ",
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()
            ];

        return response()->view("admin.accounting.changeofcapital",$supplyData);
    }

    public function printcapitalchange(Request $request){
        $data = $request->all();

        $print = new PrintController();
        return $print->printequityChangeReport($data['startDate'], $data['endDate']);
    }
}
