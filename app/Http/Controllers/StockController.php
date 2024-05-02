<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Stocks_Out;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class StockController extends Controller
{
    public function getViewInventoryIN(Request $request){
        $supplyData = [
            'title' => 'Inventory IN',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    

            ];

        return response()->view("admin.inventory.inventoryin",$supplyData);
        
    }

    public function getViewInventoryOUT(Request $request){
        $supplyData = [
            'title' => 'Inventory OUT',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    
            ];

        return response()->view("admin.inventory.inventoryout",$supplyData);
        
    }
    public function getViewStocks(Request $request){
        $supplyData = [
            'title' => 'Stocks',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    
            ];

        return response()->view("admin.inventory.stocks",$supplyData);
        
    }
    
    public function getViewStockReminder(Request $request){
        $supplyData = [
            'title' => 'Stock Reminder',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    
            ];

        return response()->view("admin.inventory.stockreminder",$supplyData);
        
    }


    public function printIIN(Request $request){

        $firstDate = $request->get('firstDate');
        $lastDate = $request->get('lastDate');

        try {
            //code...
            if( !Carbon::createFromFormat('Y-m-d', $firstDate) ){
                abort(404);
            }
            if( !Carbon::createFromFormat('Y-m-d', $lastDate) ){
                abort(404);
            }
            if (empty($firstDate)){
                abort(404);
            }
        } catch (\Throwable $th) {
            abort(404);
        }

        $printcontroller = new PrintController();
        return $printcontroller->printIIN($firstDate, $lastDate);
    }

    public function printIOUT(Request $request){

        $firstDate = $request->get('firstDate');
        $lastDate = $request->get('lastDate');

        try {
            //code...
            if( !Carbon::createFromFormat('Y-m-d', $firstDate) ){
                abort(404);
            }
            if( !Carbon::createFromFormat('Y-m-d', $lastDate) ){
                abort(404);
            }
            if (empty($firstDate)){
                abort(404);
            }
            if (empty($lastDate)){
                abort(404);
            }
        } catch (\Throwable $th) {
            abort(404);
        }

        $printcontroller = new PrintController();
        return $printcontroller->printIOUT($firstDate, $lastDate);
    }

    public function printstock(){

        $printcontroller = new PrintController();
        return $printcontroller->printStock();
    }

    public function printstockreminder(){

        $printcontroller = new PrintController();
        return $printcontroller->printStockReminder();
    }

    public function getTableInventoryIn(Request $request, DataTables $dataTables){

        if ($request->ajax()){


            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
        
            
            $stock = Stock::join("items", "stocks.item_code", "=", "items.code")
            ->join('categories', "categories.code", "=", "items.category_code")
            ->select('stocks.id', 'stocks.item_date', 'stocks.ref_no', 
            'stocks.item_code' ,'items.name as item_name', 'categories.name as item_category', 
            'stocks.unit_code', 'stocks.actual_stock', 'stocks.cogs', 'categories.coa_code')
            ->whereBetween('stocks.item_date', [$startDate,$endDate]);
            
            return $dataTables->of($stock)
                ->editColumn('item_date', function($row) {
                    return Carbon::parse($row->item_date)->format('d/m/Y');
                })
                ->editColumn('actual_stock', function($row) {
                    return floatval($row->actual_stock);
                })
                ->editColumn('cogs', function($row) {
                    return "Rp " .number_format($row->cogs,2, '.');
                })
                ->filterColumn('item_name', function($query, $keyword) {
                    $query->whereRaw("items.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('item_category', function($query, $keyword) {
                    $query->whereRaw("categories.name LIKE ?", ["%{$keyword}%"]);
                })
                ->addColumn('action', function ($row) {
                    $html = '';

                    if ($row->ref_no == 'Stock Awal'){
                        $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->id.'" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->id.'" title="Delete"><i class="fa fa-trash"></i></button>
                            </div>';
                    }       
                    
                    
                    return $html;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
                
                
        } else {
            abort(404);
        }
    }

    public function getTableInventoryOut(Request $request, DataTables $dataTables){

        if ($request->ajax()){


            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
        
            
            $stock = Stocks_Out::join("items", "stocks_out.item_code", "=", "items.code")
            ->join('categories', "categories.code", "=", "items.category_code")
            ->select('stocks_out.id', 'stocks_out.item_date', 'stocks_out.ref_no', 
            'stocks_out.item_code' ,'items.name as item_name', 'categories.name as item_category', 
            'stocks_out.unit_code', 'stocks_out.qty', 'stocks_out.cogs', 'categories.coa_code')
            ->whereBetween('stocks_out.item_date', [$startDate,$endDate]);
  
            return $dataTables->of($stock)
                ->editColumn('item_date', function($row) {
                    return Carbon::parse($row->item_date)->format('d/m/Y');
                })
                ->editColumn('qty', function($row) {
                    return floatval($row->qty);
                })
                ->editColumn('cogs', function($row) {
                    return "Rp " .number_format($row->cogs,2, '.');
                })
                ->filterColumn('item_name', function($query, $keyword) {
                    $query->whereRaw("items.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('item_category', function($query, $keyword) {
                    $query->whereRaw("categories.name LIKE ?", ["%{$keyword}%"]);
                })
                ->addIndexColumn()
                ->make(true);
                
                
        } else {
            abort(404);
        }
    }

    public function getTableStocks(Request $request, DataTables $dataTables){

        if ($request->ajax()){

            
            $stock = Stock::Rightjoin("items", "stocks.item_code", "=", "items.code")
            ->join('categories', "categories.code", "=", "items.category_code")
            ->join('units', "units.code", "=", "items.unit_code")
            ->select('items.code as item_code' ,'items.name as item_name', 'categories.name as item_category', 
            'units.code as unit_code', DB::raw('sum(stocks.actual_stock) as actual_stock'), DB::raw('sum(stocks.used_stock) as used_stock'), 
            DB::raw('sum(stocks.actual_stock) - sum(stocks.used_stock) as available_stock'))
            ->groupBy('items.code', 'items.name' ,'categories.name' ,'units.code');
    
            
            return $dataTables->of($stock)
                ->editColumn('actual_stock', function($row) {
                    return floatval($row->actual_stock);
                })
                ->editColumn('used_stock', function($row) {
                    return floatval($row->used_stock);
                })
                ->editColumn('available_stock', function($row) {
                    return floatval($row->available_stock);
                })
                ->filterColumn('item_name', function($query, $keyword) {
                    $query->whereRaw("items.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('item_code', function($query, $keyword) {
                    $query->whereRaw("items.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('unit_code', function($query, $keyword) {
                    $query->whereRaw("units.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('item_category', function($query, $keyword) {
                    $query->whereRaw("categories.name LIKE ?", ["%{$keyword}%"]);
                })
                ->addIndexColumn()
                ->make(true);
                
                
        } else {
            abort(404);
        }
    }

    public function getTableStockReminder(Request $request, DataTables $dataTables){

        if ($request->ajax()){

            
            $stock = Stock::Rightjoin("items", "stocks.item_code", "=", "items.code")
            ->join('categories', "categories.code", "=", "items.category_code")
            ->join('units', "units.code", "=", "items.unit_code")
            ->select('items.code as item_code' ,'items.name as item_name', 'categories.name as item_category', 'items.min_stock',
            'units.code as unit_code', DB::raw('ifnull(sum(stocks.actual_stock) - sum(stocks.used_stock) ,0) as current_stock'))
            ->groupBy('items.code', 'items.name' ,'categories.name' ,'units.code', 'items.min_stock')
            ->havingRaw('IFNULL(sum(stocks.actual_stock) - sum(stocks.used_stock), 0) <= (items.min_stock + 1)');


            return $dataTables->of($stock)
                ->editColumn('min_stock', function($row) {
                    return floatval($row->min_stock);
                })
                ->editColumn('current_stock', function($row) {
                    return floatval($row->current_stock);
                })
                ->filterColumn('item_name', function($query, $keyword) {
                    $query->whereRaw("items.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('item_code', function($query, $keyword) {
                    $query->whereRaw("items.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('unit_code', function($query, $keyword) {
                    $query->whereRaw("units.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('item_category', function($query, $keyword) {
                    $query->whereRaw("categories.name LIKE ?", ["%{$keyword}%"]);
                })
                ->addIndexColumn()
                ->make(true);
                
                
        } else {
            abort(404);
        }
    }

    public function stockout($itemcode = "",$qty = 0, $transdate='', $transcode){

        try {

            $stock_remaining = Stock::query()->selectRaw('IFNULL(SUM(actual_stock - used_stock), 0 ) AS Remaining_Stock')->where("item_code", $itemcode)->first()->Remaining_Stock;
            
            if(floatval($stock_remaining) == 0 || floatval($stock_remaining) < floatval($qty) ){
                throw new Exception("Insufficient Stock Of Supplies");
            }

            $stocks = Stock::where('item_code', $itemcode)
            ->whereColumn("actual_stock", ">" ,"used_stock")->lockForUpdate()->orderBy('item_date')->orderBy('id')->get();

            foreach ($stocks as $stock){

                if ($qty != 0) {
    
                    $stock_available= floatval($stock->actual_stock - $stock->used_stock);
        
                        if($qty >= $stock_available){
        
                            $stock->used_stock += $stock_available;
                            $stock->updated_by = Auth::user()->username;
                            $stock->update();
                            $loginvout = new Stocks_Out();
                            $loginvout->ref_no = $transcode;
                            $loginvout->item_code = $stock->item_code;
                            $loginvout->unit_code = $stock->unit_code;
                            $loginvout->item_date = $transdate;
                            $loginvout->qty = $stock_available;
                            $loginvout->cogs = $stock->cogs;
                            $loginvout->stock_id = $stock->id;
                            $loginvout->created_by = Auth::user()->username;
                            $loginvout->save();
                            $qty-=$stock_available;
                        } else {
            
                            $stock->used_stock += $qty;
                            $stock->update();
                            $loginvout = new Stocks_Out();
                            $loginvout->ref_no = $transcode;
                            $loginvout->item_code = $stock->item_code;
                            $loginvout->unit_code = $stock->unit_code;
                            $loginvout->item_date = $transdate;
                            $loginvout->qty = $qty;
                            $loginvout->cogs = $stock->cogs;
                            $loginvout->stock_id = $stock->id;
                            $loginvout->created_by = Auth::user()->username;
                            $loginvout->save();
                            $qty-=$qty;
                        }
                }
            }


        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
            
        }


    }
    public function refreshstock($itemcode = "",$qty = 0, $transdate='', $transcode){
        try {
            $stockout =  Stocks_Out::where('ref_no', $transcode)->where("item_code", $itemcode)->get();


            foreach($stockout as $s){
            $stock = Stock::where("id" , $s->stock_id)->first();
            $stock->used_stock -= floatval($s->qty);
            $stock->update();
            $s->delete();
            }
            $this->stockout($itemcode,$qty,$transdate,$transcode);
            
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function revertstock($transcode){
        try {
            $stockout =  Stocks_Out::where('ref_no', $transcode)->get();


            foreach($stockout as $s){
                $stock = Stock::where("id" , $s->stock_id)->first();
                $stock->used_stock -= floatval($s->qty);
                $stock->update();
                $s->delete();
            }
            
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function stockin($refno, $item_code, $unit_code, $item_date, $qty, $cogs){
        try {
            $stock = new Stock();

            $stock->ref_no = $refno;
            $stock->item_code = $item_code;
            $stock->unit_code = $unit_code;
            $stock->item_date = $item_date;
            $stock->actual_stock = $qty;
            $stock->used_stock = 0;
            $stock->cogs = $cogs;
            $stock->created_by= Auth::user()->username;
            $stock->save();

            
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }


}
