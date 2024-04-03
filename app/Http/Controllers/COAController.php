<?php

namespace App\Http\Controllers;

use App\Models\COA;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class COAController extends Controller
{
    public function getCOATableSearch(Request $request, DataTables $dataTables){
        if($request->ajax()){

            $COA =COA::query()->where('description', 'detail');

            return $dataTables->of($COA)
            
            ->addColumn('action', function ($row) {
                
                return '
                <div class="d-flex justify-content-center">
                <button class="btn btn-sm btn-success btnselectCOA" data-code="'.$row->code.'" title="Select COA"><i class="fa fa-check"></i> Select</button>
                </div>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);

        }
    }
}
