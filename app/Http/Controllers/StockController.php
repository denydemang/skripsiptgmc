<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Stocks_Out;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
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
