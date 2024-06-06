<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LedgerController extends Controller
{
    public function getViewLedger(Request $request){

        $supplyData = [
            'title' =>"Ledger",
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()
            ];

        return response()->view("admin.accounting.ledger",$supplyData);

    }

    public function printLedger(Request $request){

            $data = $request->all();

            $coaCODELIST =  $data['coa'];



            $print = new PrintController();
           return $print->printledgerreport($data['startDate'], $data['endDate'],  $coaCODELIST);

    }
}
