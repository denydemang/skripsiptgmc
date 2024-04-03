<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerController extends AdminController
{
    public function getDataCustomerForModal(Request $request, DataTables $dataTables){


        if ($request->ajax()){

            
            $customers = Customer::query();
    
            return $dataTables->of($customers)
            ->addColumn('action', function ($row) {
                
                return '
                <div class="d-flex justify-content-center">
                    <button class="btn btn-sm btn-success selectcustomerbtn" data-code="'.$row->code.'"  data-name="'.$row->name.'" title="Select"><i class="fa fa-check"></i> Select</button>
                </div>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);

        }
    }
}
