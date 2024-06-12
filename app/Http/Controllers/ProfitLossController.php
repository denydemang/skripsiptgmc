<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfitLossController extends AdminController
{
    public function getViewProfitLoss(Request $request){
        $supplyData = [
            'title' =>"Profit & Loss",
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()
            ];

        return response()->view("admin.accounting.profitloss",$supplyData);
    }

    public function printprofitloss(Request $request){
        
        $data = $request->all();

        $print = new PrintController();
        return $print->printtriallrreport($data['startDate'], $data['endDate']);
    }
}
