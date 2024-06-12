<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalanceSheetController extends Controller
{
    public function getViewBalanceSheet(Request $request){
        $supplyData = [
            'title' =>"Balance Sheet",
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()
            ];

        return response()->view("admin.accounting.balancesheet",$supplyData);
    }

    public function printbalancesheet(Request $request){
        $data = $request->all();

        $print = new PrintController();
        return $print->printbalancesheetreport($data['endDate']);
    }
}
