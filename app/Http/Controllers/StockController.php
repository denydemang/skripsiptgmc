<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Stocks_Out;
use App\Models\StocksAVG;
use App\Models\StocksInAVG;
use App\Models\StocksOutAVG;
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

    public function getViewStockCard(Request $request){
        $supplyData = [
            'title' => 'Stock Card',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    
            ];

        return response()->view("admin.inventory.stockcard",$supplyData);
        
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
    public function printIINAVG(Request $request){

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
        return $printcontroller->printIINAVG($firstDate, $lastDate);
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

    public function printIOUTAVG(Request $request){

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
        return $printcontroller->printIOUTAVG($firstDate, $lastDate);
    }

    public function printstock(){

        $printcontroller = new PrintController();
        return $printcontroller->printStock();
    }
    public function printstockavg(){

        $printcontroller = new PrintController();
        return $printcontroller->printStockAVG();
    }

    public function printstockreminder(){

        $printcontroller = new PrintController();
        return $printcontroller->printStockReminder();
    }
    public function printstockreminderavg(){

        $printcontroller = new PrintController();
        return $printcontroller->printStockReminderAVG();
    }
    
    public function printstockcard(Request $request){

        $startDate = $request->get('startdate');
        $endDate = $request->get('enddate');
        $itemcode= $request->get('itemcode');


        try {
            //code...
            if( !Carbon::createFromFormat('Y-m-d', $startDate) ){
                abort(404);
            }
            if( !Carbon::createFromFormat('Y-m-d', $endDate) ){
                abort(404);
            }
            if (!$startDate || !$endDate || !$itemcode){
                abort(404);
            }
        } catch (\Throwable $th) {
            abort(404);
        }



        $printcontroller = new PrintController();
        return $printcontroller->printstockcard($startDate, $endDate, $itemcode);
    }

    public function printstockcardAVG(Request $request){

        $startDate = $request->get('startdate');
        $endDate = $request->get('enddate');
        $itemcode= $request->get('itemcode');


        try {
            //code...
            if( !Carbon::createFromFormat('Y-m-d', $startDate) ){
                abort(404);
            }
            if( !Carbon::createFromFormat('Y-m-d', $endDate) ){
                abort(404);
            }
            if (!$startDate || !$endDate || !$itemcode){
                abort(404);
            }
        } catch (\Throwable $th) {
            abort(404);
        }



        $printcontroller = new PrintController();
        return $printcontroller->printstockcardavg($startDate, $endDate, $itemcode);
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

    public function getTableInventoryInAVG(Request $request, DataTables $dataTables){

        if ($request->ajax()){


            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
        
            
            $stock = StocksInAVG::join("items", "stocksin_avg.item_code", "=", "items.code")
            ->join('categories', "categories.code", "=", "items.category_code")
            ->select('stocksin_avg.id', 'stocksin_avg.iinno', 'stocksin_avg.ref_no','stocksin_avg.item_date',
            'stocksin_avg.item_code' ,'items.name as item_name', 'categories.name as item_category', 
            'stocksin_avg.unit_code', 'stocksin_avg.qty', 'stocksin_avg.cogs', 'stocksin_avg.total','categories.coa_code')
            ->whereBetween('stocksin_avg.item_date', [$startDate,$endDate]);
            
            return $dataTables->of($stock)
                ->editColumn('item_date', function($row) {
                    return Carbon::parse($row->item_date)->format('d/m/Y');
                })
                ->editColumn('qty', function($row) {
                    return floatval($row->qty);
                })
                ->editColumn('cogs', function($row) {
                    return "Rp " .number_format($row->cogs,2,',', '.');
                })
                ->editColumn('total', function($row) {
                    return "Rp " .number_format($row->total,2,',', '.');
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
                    return "Rp " .number_format($row->cogs,2, ',','.');
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
    public function getTableInventoryOutAVG(Request $request, DataTables $dataTables){

        if ($request->ajax()){


            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
        
            
            $stock = StocksOutAVG::join("items", "stocksout_avg.item_code", "=", "items.code")
            ->join('categories', "categories.code", "=", "items.category_code")
            ->select('stocksout_avg.id','stocksout_avg.ioutno', 'stocksout_avg.item_date', 'stocksout_avg.ref_no', 
            'stocksout_avg.item_code' ,'items.name as item_name', 'categories.name as item_category', 
            'stocksout_avg.unit_code', 'stocksout_avg.qty', 'stocksout_avg.cogs', 'stocksout_avg.total', 'categories.coa_code')
            ->whereBetween('stocksout_avg.item_date', [$startDate,$endDate]);

            return $dataTables->of($stock)
                ->editColumn('item_date', function($row) {
                    return Carbon::parse($row->item_date)->format('d/m/Y');
                })
                ->editColumn('qty', function($row) {
                    return floatval($row->qty);
                })
                ->editColumn('cogs', function($row) {
                    return "Rp " .number_format($row->cogs,2,",", '.' );
                })
                ->editColumn('total', function($row) {
                    return "Rp " .number_format($row->total,2,',', '.');
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
    public function getTableStocksAVG(Request $request, DataTables $dataTables){

        if ($request->ajax()){

            
            $stock = StocksAVG::Rightjoin("items", "stocksavg.item_code", "=", "items.code")
            ->join('categories', "categories.code", "=", "items.category_code")
            ->join('units', "units.code", "=", "items.unit_code")
            ->select('items.code as item_code' ,'items.name as item_name', 'categories.name as item_category', 
            'units.code as unit_code', DB::raw('ifnull(sum(stocksavg.actual_stock),0) as actual_stock'), DB::raw('ifnull(sum(stocksavg.used_stock),0) as used_stock'), 
            DB::raw('ifnull(sum(stocksavg.actual_stock) - sum(stocksavg.used_stock) ,0) as available_stock'),
            DB::raw('ifnull(sum(stocksavg.total_in) - sum(stocksavg.total_out) ,0) as total'),
            DB::raw('ifnull((sum(stocksavg.total_in) - sum(stocksavg.total_out)) / (sum(stocksavg.actual_stock) - sum(stocksavg.used_stock)) ,0) as cogs')
            )
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
                ->editColumn('cogs', function($row) {
                    return number_format($row->cogs, 2, ',', '.');
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
    public function getTableStockReminderAVG(Request $request, DataTables $dataTables){

        if ($request->ajax()){

            
            $stock = StocksAVG::Rightjoin("items", "stocksavg.item_code", "=", "items.code")
            ->join('categories', "categories.code", "=", "items.category_code")
            ->join('units', "units.code", "=", "items.unit_code")
            ->select('items.code as item_code' ,'items.name as item_name', 'categories.name as item_category', 'items.min_stock',
            'units.code as unit_code', DB::raw('ifnull(sum(stocksavg.actual_stock) - sum(stocksavg.used_stock) ,0) as current_stock'))
            ->groupBy('items.code', 'items.name' ,'categories.name' ,'units.code', 'items.min_stock')
            ->havingRaw('IFNULL(sum(stocksavg.actual_stock) - sum(stocksavg.used_stock), 0) <= (items.min_stock + 1)');


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

    public function stockoutAVG($IOUTNO,$unit_code, $itemcode = "",$qty = 0, $transdate='', $transcode){

        try {

            DB::beginTransaction();
            $stock_remaining = StocksAVG::query()->selectRaw('IFNULL(SUM(actual_stock) - SUM(used_stock), 0 ) AS Remaining_Stock')->where("item_code", $itemcode)->first()->Remaining_Stock;
            $cogs = StocksAVG::query()->selectRaw('ifnull((sum(stocksavg.total_in) - sum(stocksavg.total_out)) / (sum(stocksavg.actual_stock) - sum(stocksavg.used_stock)) ,0) as cogs')->where("item_code", $itemcode)->first()->cogs;
            if(floatval($stock_remaining) == 0 || floatval($stock_remaining) < floatval($qty) ){
                throw new Exception("Insufficient Stock Of Supplies");
            }
            $IIN = new StocksOutAVG();
            $IIN->ioutno = $IOUTNO;
            $IIN->ref_no = $transcode;
            $IIN->item_code = $itemcode;
            $IIN->unit_code = $unit_code;
            $IIN->item_date = $transdate;
            $IIN->qty = $qty;
            $IIN->cogs = round($cogs,4);
            $IIN->total = round($qty * floatval($cogs),4);
            $IIN->created_by = Auth::user()->username;
            $IIN->save();
            
            // $stock->iinno = $iinno;
            $stock = new StocksAVG();

            $stock->ref_no = $IOUTNO;
            $stock->item_code = $itemcode;
            $stock->unit_code = $unit_code;
            $stock->item_date = $transdate;
            $stock->actual_stock =0;
            $stock->used_stock =  $qty;
            $stock->total_in = 0;
            $stock->total_out = $IIN->total;
            $stock->created_by= Auth::user()->username;
            $stock->save();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
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

    public function stockinAVG(string $iinno ,string $refno, string $item_code, string $unit_code,string $item_date, float $price, float $qty, float $total){
        try {

            DB::beginTransaction();
            $IIN = new StocksInAVG();
            $IIN->iinno = $iinno;
            $IIN->ref_no = $refno;
            $IIN->item_code = $item_code;
            $IIN->unit_code = $unit_code;
            $IIN->item_date = $item_date;
            $IIN->qty = $qty;
            $IIN->cogs = $price;
            $IIN->total = $total;
            $IIN->created_by = Auth::user()->username;
            $IIN->save();
            
            // $stock->iinno = $iinno;
            $stock = new StocksAVG();

            $stock->ref_no = $iinno;
            $stock->item_code = $item_code;
            $stock->unit_code = $unit_code;
            $stock->item_date = $item_date;
            $stock->actual_stock = $qty;
            $stock->used_stock = 0;
            $stock->total_in = $total;
            $stock->total_out = 0;
            $stock->created_by= Auth::user()->username;
            $stock->save();

            DB::commit();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    public function isAllowedUpdateDeleteAVG(string $refno, string $item_code , string $type = "IN" ) : bool{

        if ($type == "IN"){
            $IINNO = StocksInAVG::where("ref_no", $refno)->first()->iinno;
            $maxID = StocksAVG::where("ref_no" , $IINNO)->where("item_code" , $item_code)->orderBy("id", "desc")->first()->id;
    
            $stocks = StocksAVG::where("id" , ">", $maxID)->where("item_code", $item_code)->where("used_stock", ">", 0)->get();
    
            return count($stocks) == 0;
        } else {
            $ioutno = StocksOutAVG::where("ref_no", $refno)->first()->ioutno;
            $maxID = StocksAVG::where("ref_no" , $ioutno)->where("item_code" , $item_code)->orderBy("id", "desc")->first()->id;
    
            $stocks = StocksAVG::where("id" , ">", $maxID)->where("item_code", $item_code)->where("used_stock", ">", 0)->get();
    
            return count($stocks) == 0;
        }
    
    }

    public function revertstockinAVG(string $refno, string $item_code, string $unit_code,string $item_date, float $price, float $qty, float $total){
        try {
            DB::beginTransaction();

            if (!$this->isAllowedUpdateDeleteAVG($refno, $item_code , "IN")){
                throw new Exception("Cannot Update Or Delete Transaction Already Used!");
            }

            $IINNo = StocksInAVG::where("ref_no", $refno)->first()->iinno;
            StocksInAVG::where("ref_no", $refno)->where("item_code", $item_code)->delete();
            StocksAVG::where("ref_no", $IINNo)->where("item_code", $item_code)->delete();
            $this->stockinAVG($IINNo, $refno,$item_code,$unit_code,$item_date,$price, $qty, $total);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw new \Exception($th->getMessage());
        }
    }

    public function revertstockoutAVG(string $IOUTNO, string $refno, string $item_code, string $unit_code,string $item_date, float $qty){
        try {
            DB::beginTransaction();

            if (!$this->isAllowedUpdateDeleteAVG($refno, $item_code , "OUT")){
                throw new Exception("Cannot Update Or Delete Transaction Already Used!");
            }

            $ioutno = StocksOutAVG::where("ref_no", $refno)->first()->ioutno;
            StocksOutAVG::where("ref_no", $refno)->where("item_code", $item_code)->delete();
            StocksAVG::where("ref_no", $ioutno)->where("item_code", $item_code)->delete();
            $this->stockoutAVG($IOUTNO,$unit_code,$item_code ,$qty, $item_date, $refno);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw new \Exception($th->getMessage());
        }
    }

    public function deleteStockINAVG(string $refno){

        try {
            DB::beginTransaction();
            $IINNO = StocksInAVG::where("ref_no", $refno)->first()->iinno;
            $stocks = StocksAVG::where("ref_no", $IINNO)->get();

            foreach($stocks as $s){
                if (!$this->isAllowedUpdateDeleteAVG($refno, $s->item_code , "IN")){
                    throw new Exception("Cannot Update Or Delete Transaction Already Used!");
                }
            }
            $IINNo = StocksInAVG::where("ref_no", $refno)->first()->iinno;
            StocksInAVG::where("ref_no", $refno)->delete();
            StocksAVG::where("ref_no", $IINNo)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw new \Exception($th->getMessage());
        }

    }
    public function deleteStockOUTAVG(string $refno){

        try {
            DB::beginTransaction();
            $ioutno = StocksOutAVG::where("ref_no", $refno)->first()->ioutno;
            $stocks = StocksAVG::where("ref_no", $ioutno)->get();

            foreach($stocks as $s){
                if (!$this->isAllowedUpdateDeleteAVG($refno, $s->item_code , "OUT")){
                    throw new Exception("Cannot Update Or Delete Transaction Already Used!");
                }
            }
            $ioutno = StocksOutAVG::where("ref_no", $refno)->first()->ioutno;
            StocksOutAVG::where("ref_no", $refno)->delete();
            StocksAVG::where("ref_no", $ioutno)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw new \Exception($th->getMessage());
        }

    }


}
