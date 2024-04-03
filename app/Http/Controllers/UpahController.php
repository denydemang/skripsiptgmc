<?php

namespace App\Http\Controllers;

use App\Models\Upah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UpahController extends AdminController
{
    public function getTableUpahSearch(Request $request, DataTables $dataTables){

        if ($request->ajax()){

            $upah = Upah::query();
    
                return $dataTables->of($upah)
                ->addColumn('action', function ($row) {
                    $data = '<div class="d-flex justify-content-center">
                    <button class="btn btn-sm btn-success selectupahbtn" data-code="'.$row->code.'" data-job="'.$row->job.'" data-description="'.$row->description.'" data-unit="'.$row->unit.'" data-price="'.$row->price.'" title="Select Upah"><i class="fa fa-check"></i> Select</button>
                    </div>';    
                return $data;
                })
                ->editColumn('price', function($row) {
                    return "Rp ". number_format($row->price,2,",",".");
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);


        }

    }
}
