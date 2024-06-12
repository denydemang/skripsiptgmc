<?php

namespace App\Http\Controllers;

use App\Models\Advanced_Receipt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class AdvanceReceiptController extends AdminController
{
    public function getViewAdvancedReceipt(Request $request){
         $supplyData = [
            'title' => 'Advanced Receipt',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            ];

        return response()->view("admin.finance.advancedreceipt",$supplyData);
    }

    public function getViewAdvancedReceiptManage(Request $request, $code=null){

        $data= [];
        if ($code){ //If In Update Mode


            $AR = Advanced_Receipt::join("customers", "advanced_receipts.customer_code", "=", "customers.code")
            ->join("coa", "advanced_receipts.coa_debit", "=", "coa.code")
            ->select("advanced_receipts.*","coa.code as coa_code","coa.name as coa_name" , "customers.code as customer_code","customers.name as customer_name")
            ->where("adr_no", $code)
            ->where("is_approve" , 0)
            ->first();

            if (!$AR){
                abort(404);
            }

            $data['AR'] = $AR;
        }
        $supplyData = [
            'title' =>$request->route()->getName() == 'admin.addAdvancedReceiptView' ?  'Add New Advanced Receipt' : 'Edit Advanced Receipt',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            'data' => $data
            ];

        return response()->view("admin.finance.advancedreceiptmanage",$supplyData);
    }

    public function getTableAR(Request $request, DataTables $dataTables ){
        if ($request->ajax()){

            $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');


            $AR = Advanced_Receipt::join("customers", "advanced_receipts.customer_code", "=", "customers.code")
            ->whereBetween('transaction_date', [$startDate,$endDate])
            ->select("advanced_receipts.*", "customers.code as customer_code","customers.name as customer_name")
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            });

            return $dataTables->of($AR)
                ->editColumn('transaction_date', function($row) {
                    return Carbon::parse($row->transaction_date)->format('d/m/Y');
                })
                ->editColumn('deposit_amount', function($row) {
                    return "Rp " .number_format($row->deposit_amount,2, ',', '.');
                })
                ->editColumn('deposit_allocation', function($row) {
                    return "Rp " .number_format($row->deposit_allocation,2, ',', '.');
                })
                ->editColumn('is_approve', function($row) {
                    $html ="";
                    switch ($row->is_approve) {
                        case 0:
                            $html= "<span class='badge badge-warning'>Not Approve</span>";
                            break;
                        case 1:
                            $html= "<span class='badge badge-primary'>Approved</span>";
                            break;
                    }
                    return $html;
                })
                ->filterColumn('coa_code', function($query, $keyword) {
                    $query->whereRaw("coa.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('coa_name', function($query, $keyword) {
                    $query->whereRaw("coa.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('customer_code', function($query, $keyword) {
                    $query->whereRaw("customers.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('customer_name', function($query, $keyword) {
                    $query->whereRaw("customers.name LIKE ?", ["%{$keyword}%"]);
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    switch ($row->is_approve) {
                        case 0: //Not Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->adr_no.'" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->adr_no.'" title="Delete"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-sm btn-warning approvebtn" data-code="'.$row->adr_no.'" title="Approve"><i class="fa fa-check"></i></button>
                            <a href="'.route('admin.printdetailar',['id' => $row->adr_no]).'" target="_blank"><button class="btn btn-sm btn-success printbtn" data-code="'.$row->cash_no.'" title="Print Detail"><i class="fa fa-print"></i></button></a>
                            </div>';

                            # code...
                            break;
                            case 1: //Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <a class="mr-2" href="'.route('admin.printdetailar',['id' => $row->adr_no]).'" target="_blank"><button class="btn btn-sm btn-success printbtn" data-code="'.$row->cash_no.'" title="Print Detail"><i class="fa fa-print"></i></button></a>
                            <a href="'.route('admin.printjurnalar',['id' => $row->adr_no]).'" target="_blank"><button class="btn btn-sm btn-warning printbtn" data-code="'.$row->cash_no.'" title="Print Jurnal"><i class="fa fa-print"></i></button></a>
                            </div>';

                            break;

                        default:
                            # code...
                            break;
                    }

                    return $html;
                })
                ->rawColumns(['action','is_approve'])
                ->addIndexColumn()
                ->make(true);

        } else {
            abort(404);
        }

    }

    public function deletear($id){
        try {

            Advanced_Receipt::where("adr_no", $id)->delete();
            return response()->redirectToRoute("admin.advancedreceipt")->with("success", "Data AdvancedReceipt $id Successfully Deleted");

        } catch (\Throwable $th) {
            return $this->errorException($th,"admin.advancedreceipt", $id );
        }
    }

    public function approvear($id){
        try {

            DB::beginTransaction();

            Advanced_Receipt::where("adr_no", $id)->update(
                [

                    'is_approve' => 1,
                    'approved_by' => Auth::user()->name
                ]
            );

            $journal = new AccountingController();
            $journal->journalAdvancedReceipt($id);

            DB::commit();
            return response()->redirectToRoute("admin.advancedreceipt")->with("success", "Data Advanced Receipt $id Successfully Approved");
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorException($th,"admin.advancedreceipt", $id );
        }
    }

    public function addAR(Request $request){
        if($request->ajax()){

            try {
                //code...
                DB::beginTransaction();
                $data = $request->all();
                $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');

                $supplymodel = Advanced_Receipt::orderBy("adr_no", "desc")->lockforUpdate()->first();
                $code = $this->automaticCode('ADR' ,$supplymodel, true,  'adr_no');

                $AR = new Advanced_Receipt();
                $AR->adr_no = $code;
                $AR->transaction_date = $data['transaction_date'];
                $AR->customer_code = $data['customer_code'];
                $AR->coa_debit = $data['coa_debit'];
                $AR->coa_kredit = $data['coa_credit'];
                $AR->deposit_amount = floatval($data['deposit_amount']);
                $AR->deposit_allocation = 0;
                $AR->description = $data['description'] ;
                $AR->is_approve = 0;
                $AR->created_by = Auth::user()->username;
                $AR->save();

                DB::commit();
                Session::flash('success',  "New AdvancedReceive : $AR->adr_no Succesfully Created");
                return json_encode(true);

            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());

            }

        } else {
            abort(404);
        }

    }
    public function editAR($code,Request $request){
        if($request->ajax()){

            try {
                //code...
                DB::beginTransaction();
                $data = $request->all();
                $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');

                $AR = Advanced_Receipt::where('adr_no', $code)->first();
                $AR->transaction_date = $data['transaction_date'];
                $AR->customer_code = $data['customer_code'];
                $AR->coa_debit = $data['coa_debit'];
                $AR->coa_kredit = $data['coa_credit'];
                $AR->deposit_amount = floatval($data['deposit_amount']);
                $AR->deposit_allocation = 0;
                $AR->description = $data['description'] ;
                $AR->is_approve = 0;
                $AR->updated_by = Auth::user()->username;
                $AR->update();

                DB::commit();
                Session::flash('success',  "AdvancedReceive : $AR->adr_no Succesfully Updated");
                return json_encode(true);

            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());

            }

        } else {
            abort(404);
        }

    }

    public function printjurnalar($id){

        $printcontroller = new PrintController();
        return $printcontroller->printjurnalar($id);
    }

    public function printdetailar($id){
        $printcontroller = new PrintController();
        return $printcontroller->printdetailadr($id);
    }

    public function printrecapar(Request $request){
        $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
        $startDate = $request->startDate;
        $endDate =  $request->endDate;
        $customercode = $request->customercode;

        $printcontroller = new PrintController();
        return $printcontroller->printrecapar($customercode,$startDate, $endDate, $is_approve);
    }

    public function decreaseDeposit(float $amount , string $customer_code){

        $depo_amount = $amount;
        $ADR = Advanced_Receipt::where('customer_code',$customer_code)->where("deposit_allocation" , ">" , 0)->orderBy("transaction_date", "desc")->orderBy("adr_no", "desc")->get();

        foreach($ADR as $x){
            if ($depo_amount > 0){
                if($x->deposit_allocation >= $depo_amount){
                    $x->deposit_allocation = floatval($x->deposit_allocation) - $depo_amount;
                    $depo_amount -= $depo_amount;
                    $x->update();
                } else {
                    $x->deposit_allocation = floatval($x->deposit_allocation) - floatval($x->deposit_allocation) ;
                    $depo_amount -= floatval($x->deposit_allocation);
                    $x->update();
                }
            }
        }
    }

    public function increaseDeposit(float $amount , string $customer_code){
        $depo_amount = $amount;
        $ADR = Advanced_Receipt::where('customer_code', $customer_code)->whereColumn("deposit_amount", ">" ,"deposit_allocation")->orderBy("transaction_date", "desc")->orderBy("adr_no", "desc")->get();

        foreach($ADR as $x){
            if ($depo_amount > 0){
                $balance_amount = floatval($x->deposit_amount) - floatval($x->deposit_allocation);

                if ($balance_amount >= $depo_amount ){

                    $x->deposit_allocation = floatval($x->deposit_allocation) + $depo_amount;
                    $x->update();
                    $depo_amount -= $depo_amount;

                } else {
                    $x->deposit_allocation = floatval($x->deposit_allocation) + $balance_amount ;
                    $x->update();
                    $depo_amount -=  $balance_amount;
                }
            }
        }
    }
}
