<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrialBalanceConttroller extends AdminController
{
    public function getViewTrialBalance(Request $request){
        $supplyData = [
            'title' =>"Trial Balance",
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()
            ];

        return response()->view("admin.accounting.trialbalance",$supplyData);
    }

    public function printtrialbalance(Request $request){
        
        $data = $request->all();

        $print = new PrintController();
        return $print->printtrialbalancereport($data['startDate'], $data['endDate']);
    }

}
