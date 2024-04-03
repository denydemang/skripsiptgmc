<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ItemController extends AdminController
{
    public function getTableItemSearch(Request $request, DataTables $dataTables){

        if ($request->ajax()){

            $items = Item::leftjoin('stocks',"items.code", "=", "stocks.item_code" )
            ->leftjoin('categories', 'items.category_code', '=', 'categories.code' )
                ->select('items.code', 'items.name', 'items.unit_code', 'categories.code as category_code',
                'categories.name as category_name', 'items.min_stock', 'items.max_stock', DB::raw('IFNULL(SUM(stocks.actual_stock - stocks.used_stock), 0) As stocks'))
                ->groupBy('items.code', 'items.name', 'items.unit_code', 'categories.code', 'categories.name', 'items.min_stock', 'items.max_stock');
    
                return $dataTables->of($items)
                ->addColumn('action', function ($row) {
                    $data = '';
                    if($row->stocks <= 0){
                        $data = '<div class="d-flex justify-content-center">
                        <button style="cursor: not-allowed" onmouseover="this.style.cursor="crosshair"" disabled class="btn btn-sm btn-success selectitembtn" data-stocks="'.$row->stocks.'" data-min_stock="'.$row->min_stock.'" data-max_stock="'.$row->max_stock.'" data-unit="'.$row->unit_code.'" data-code="'.$row->code.'"  data-name="'.$row->name.'" title="Not Allowed Not Enough Stock"><i class="fa fa-check"></i> Select</button>
                        </div>';
                    } else {
                        $data = '<div class="d-flex justify-content-center">
                        <button class="btn btn-sm btn-success selectitembtn" data-stocks="'.$row->stocks.'" data-min_stock="'.$row->min_stock.'" data-max_stock="'.$row->max_stock.'" data-unit="'.$row->unit_code.'" data-code="'.$row->code.'"  data-name="'.$row->name.'" title="Select Item"><i class="fa fa-check"></i> Select</button>
                        </div>';
                    }
    
                    return $data;
                })
                ->editColumn('stocks', function($row) {
                    return (float)$row->stocks;
                })
                ->editColumn('min_stock', function($row) {
                    return (float)$row->min_stock;
                })
                ->editColumn('max_stock', function($row) {
                    return (float)$row->max_stock;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);


        }

    }
}
