<?php

namespace App\Http\Controllers;

use App\Models\Advanced_Receipt;
use App\Models\Cash_Receive;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ReceiptController extends AdminController
{
    public function getViewReceipt(Request $request){
        $supplyData = [
            'title' => 'Receipt',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            ];

        return response()->view("admin.finance.receipt",$supplyData);
    }

    public function getViewReceiptManage(Request $request, $code=null){

        $data= [];
        if ($code){ //If In Update Mode

            $receipt = Cash_Receive::where("bkm_no", $code)
            ->where("is_approve" , 0)
            ->join("customers", "cash_receives.customer_code", "=", "customers.code")
            ->select('cash_receives.*', 'customers.code as customer_code', 'customers.name as customer_name')
            ->first();

            if (!$receipt){
                abort(404);
            }

            $subQuery = DB::table('invoices as i')
            ->select(
                'i.invoice_no',
                'i.transaction_date',
                'i.customer_code',
                'i.is_approve',
                DB::raw("
                    CASE
                        WHEN i.payment_term_code LIKE 'n/30' THEN DATE_ADD(i.transaction_date, INTERVAL 30 DAY)
                        WHEN i.payment_term_code LIKE 'n/60' THEN DATE_ADD(i.transaction_date, INTERVAL 60 DAY)
                        WHEN i.payment_term_code LIKE 'n/90' THEN DATE_ADD(i.transaction_date, INTERVAL 90 DAY)
                    END AS due_date
                "),
                DB::raw('(i.grand_total - i.paid_amount) AS balance')
            );

            // Use the subquery in the main query
            $results = DB::table(DB::raw("({$subQuery->toSql()}) as qry"))
            ->mergeBindings($subQuery)
            ->select('qry.invoice_no','qry.customer_code', 'qry.transaction_date','qry.is_approve', 'qry.due_date', 'qry.balance')
            ->where("qry.invoice_no", "=", $receipt->ref_no)
            ->first();

        $data['receipt'] = $receipt;
        $data['detail'] = json_encode($results);

        $balanceAR = Advanced_Receipt::select(DB::raw('ifnull(SUM(deposit_amount - deposit_allocation), 0) as balance'))
        ->where("customer_code", $code)
        ->where('is_approve', 1)
        ->first()->balance;
        $data['deposit_amount'] = floatval($balanceAR);

        }
        $supplyData = [
            'title' =>$request->route()->getName() == 'admin.addReceiptView' ?  'Add New Receipt' : 'Edit Receipt',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            'data' => $data
            ];

        return response()->view("admin.finance.receiptmanage",$supplyData);
    }

    public function getbalancear($code, Request $request){
        if($request->ajax()){

            $balanceAR = Advanced_Receipt::select(DB::raw('ifnull(SUM(deposit_amount - deposit_allocation), 0) as balance'))
            ->where("customer_code", $code)
            ->where('is_approve', 1)
            ->first()->balance;
            return response()->json($balanceAR);


        } else {
            abort(404);
        }

    }

    public function getinvoiceforreceipt($code, Request $request){

        if ($request->ajax()){

    // Create the subquery
            $subQuery = DB::table('invoices as i')
            ->select(
                'i.invoice_no',
                'i.transaction_date',
                'i.customer_code',
                'i.is_approve',
                DB::raw("
                    CASE
                        WHEN i.payment_term_code LIKE 'n/30' THEN DATE_ADD(i.transaction_date, INTERVAL 30 DAY)
                        WHEN i.payment_term_code LIKE 'n/60' THEN DATE_ADD(i.transaction_date, INTERVAL 60 DAY)
                        WHEN i.payment_term_code LIKE 'n/90' THEN DATE_ADD(i.transaction_date, INTERVAL 90 DAY)
                    END AS due_date
                "),
                DB::raw('(i.grand_total - i.paid_amount) AS balance')
            );

            // Use the subquery in the main query
            $results = DB::table(DB::raw("({$subQuery->toSql()}) as qry"))
            ->mergeBindings($subQuery)
            ->select('qry.invoice_no','qry.customer_code', 'qry.transaction_date','qry.is_approve', 'qry.due_date', 'qry.balance')
            ->where("qry.customer_code", "=", $code)
            ->where('qry.balance' , ">", 0)
            ->where("qry.due_date", ">=" , DB::raw("CURDATE()"))
            ->where("qry.is_approve", "=" , 1)
            ->get();

            return response()->json($results);

        } else {
            abort(404);
        }
    }

    public function getTableReceipt(Request $request, DataTables $dataTables) {
        if ($request->ajax()){


            $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');


            $payment = Cash_Receive::join("customers", "cash_receives.customer_code" , "=", "customers.code")
            ->select('cash_receives.*', DB::raw('ifnull(customers.name ,"-" ) as customer_name'),  DB::raw('ifnull(customers.address ,"-" ) as customer_address'), DB::raw('ifnull(customers.phone,"-" ) as customer_phone'))
            ->whereBetween('transaction_date', [$startDate,$endDate])
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            });

            return $dataTables->of($payment)
                ->editColumn('transaction_date', function($row) {
                    return Carbon::parse($row->transaction_date)->format('d/m/Y');
                })
                ->editColumn('deposit_amount', function($row) {
                    return "Rp " .number_format($row->deposit_amount,2, ',', '.');
                })
                ->editColumn('cash_amount', function($row) {
                    return "Rp " .number_format($row->cash_amount,2, ',', '.');
                })
                ->editColumn('total_amount', function($row) {
                    return "Rp " .number_format($row->total_amount,2, ',', '.');
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

                ->filterColumn('customer_name', function($query, $keyword) {
                    $query->whereRaw("customers.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('customer_address', function($query, $keyword) {
                    $query->whereRaw("customers.address LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('customer_phone', function($query, $keyword) {
                    $query->whereRaw("customers.phone LIKE ?", ["%{$keyword}%"]);
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    switch ($row->is_approve) {
                        case 0: //Not Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->bkm_no.'" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->bkm_no.'" title="Delete"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-sm btn-warning approvebtn" data-code="'.$row->bkm_no.'" title="Approve"><i class="fa fa-check"></i></button>
                            <a href="'.route('admin.printdetailreceipt',['id' => $row->bkm_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn" data-code="'.$row->bkm_no.'" title="Print Receipt"><i class="fa fa-print"></i></button></a>
                            </div>';

                            # code...
                            break;
                            case 1: //Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <a href="'.route('admin.printdetailreceipt',['id' => $row->bkm_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn mr-2" data-code="'.$row->bkm_no.'" title="Print Receipt"><i class="fa fa-print"></i></button></a>
                            <a href="'.route('admin.printjurnalreceipt',['id' => $row->bkm_no]).'" target="_blank"><button class="btn btn-sm btn-warning printbtn" data-code="'.$row->bkm_no.'" title="Print Jurnal"><i class="fa fa-print"></i></button></a>
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

    public function approvereceipt($id){
        try {

            DB::beginTransaction();

            Cash_Receive::where("bkm_no", $id)->update(
                [

                    'is_approve' => 1,
                    'approved_by' => Auth::user()->name
                ]
            );

            $journal = new AccountingController();
            $journal->journalReceipt($id);

            DB::commit();
            return response()->redirectToRoute("admin.receipt")->with("success", "Data Receipt $id Successfully Approved");
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorException($th,"admin.receipt", $id );
        }
    }


    public function deletereceipt($id){
        try {
            DB::beginTransaction();
            $CR = Cash_Receive::where("bkm_no",$id )->first();
            $invoices = Invoice::where("invoice_no",$CR->ref_no)->first();
            $invoices->paid_amount = $invoices->paid_amount - $CR->total_amount;
            $invoices->update();
            if (floatval($CR->deposit_amount) > 0){
                $AR = new AdvanceReceiptController();
                $AR->decreaseDeposit(floatval($CR->deposit_amount), $CR->customer_code);
            }
            $CR->delete();
            DB::commit();

            return response()->redirectToRoute("admin.receipt")->with("success", "Data Receipt $id Successfully Deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            // Session::flash('error', $th->getMessage());
            return $this->errorException($th,"admin.receipt", $id );
        }
    }

    public function printjurnalreceipt($id){
        $printcontroller = new PrintController();
        return $printcontroller->printjurnalreceipt($id);
    }
    public function printdetailreceipt($id){
        $printcontroller = new PrintController();
        return $printcontroller->printdetailreceipt($id);
    }

    public function printrecapreceipt(Request $request){
        $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
        $startDate = $request->startDate;
        $endDate =  $request->endDate;
        $customercode = $request->customercode;

        $printcontroller = new PrintController();
        return $printcontroller->printrecapreceipt($customercode,$startDate, $endDate, $is_approve);
    }

    public function addreceipt(Request $request){
        if($request->ajax()){


            try {
                //code...
                DB::beginTransaction();
                $data = $request->all();

                $details= json_decode($data['detail']);
                $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');
                $listBKKNo = [];
                foreach ($details as $key => $value) {

                    if (floatval($value->cash_amount) > 0 || floatval($value->deposit_amount) > 0 ){

                        $receipt = Cash_Receive::orderBy("bkm_no", "desc")->lockforUpdate()->first();
                        $bkmno = $this->automaticCode('BKM' ,$receipt, true,  'bkm_no');
                        $insertreceipt = new Cash_Receive();
                        $insertreceipt->transaction_date =  $data['transaction_date'];
                        $insertreceipt->bkm_no = $bkmno;
                        $insertreceipt->customer_code = $data['customer_code'];
                        $insertreceipt->received_via = $data['received_via'];
                        $insertreceipt->ref_no = $value->ref_no;
                        $insertreceipt->coa_cash_code = $data['coa_cash_code'];
                        $insertreceipt->cash_amount = floatval($value->cash_amount);
                        $insertreceipt->deposit_amount = floatval($value->deposit_amount);
                        $insertreceipt->total_amount = floatval($value->cash_amount) + floatval($value->deposit_amount);
                        $insertreceipt->description = $data['description'];
                        $insertreceipt->terbilang = '';
                        $insertreceipt->is_approve = 0;
                        $insertreceipt->approved_by = "";
                        $insertreceipt->created_by = Auth::user()->username;
                        $insertreceipt->save();

                        $inv = Invoice::where("invoice_no" , $value->ref_no)->first();

                        $inv->paid_amount = $inv->paid_amount + floatval($insertreceipt->total_amount);
                        $inv->update();


                        if (floatval($value->deposit_amount) > 0){
                            $depo_amount = floatval($value->deposit_amount);
                            $ADR = Advanced_Receipt::where('customer_code', $data['customer_code'])->whereColumn("deposit_amount", ">" ,"deposit_allocation")->orderBy("transaction_date", "desc")->orderBy("adr_no", "desc")->get();

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


                        array_push($listBKKNo,$bkmno);

                    }
                }

                $commaSeparated = implode(', ', $listBKKNo);
                DB::commit();
                Session::flash('success',  "New Receipt : $commaSeparated Succesfully Created");
                return json_encode(true);

            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }



        } else {
            abort(404);
        }
    }

    public function editreceipt($code ,Request $request ){
        if($request->ajax()){

            if($request->ajax()){


                try {
                    //code...
                    DB::beginTransaction();
                    $data = $request->all();

                    $details= json_decode($data['detail']);
                    $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');
                    foreach ($details as $key => $value) {

                        if (floatval($value->cash_amount) > 0 || floatval($value->deposit_amount) > 0 ){

                            $insertreceipt = Cash_Receive::where("bkm_no", $code)->first();

                            $inv = Invoice::where("invoice_no" , $value->ref_no)->first();

                            $inv->paid_amount = $inv->paid_amount - floatval($insertreceipt->total_amount);
                            $inv->update();

                            if (floatval($insertreceipt->deposit_amount) > 0){
                                $AR = new AdvanceReceiptController();
                                $AR->decreaseDeposit(floatval($insertreceipt->deposit_amount), $insertreceipt->customer_code);
                            }

                            $insertreceipt->transaction_date =  $data['transaction_date'];
                            $insertreceipt->customer_code = $data['customer_code'];
                            $insertreceipt->received_via = $data['received_via'];
                            $insertreceipt->ref_no = $value->ref_no;
                            $insertreceipt->coa_cash_code = $data['coa_cash_code'];
                            $insertreceipt->cash_amount = floatval($value->cash_amount);
                            $insertreceipt->deposit_amount = floatval($value->deposit_amount);
                            $insertreceipt->total_amount = floatval($value->cash_amount) + floatval($value->deposit_amount);
                            $insertreceipt->description = $data['description'];
                            $insertreceipt->terbilang = '';
                            $insertreceipt->is_approve = 0;
                            $insertreceipt->approved_by = "";
                            $insertreceipt->updated_by = Auth::user()->username;
                            $insertreceipt->update();

                            $inv = Invoice::where("invoice_no" , $value->ref_no)->first();

                            $inv->paid_amount = $inv->paid_amount + floatval($insertreceipt->total_amount);
                            $inv->update();


                            if (floatval($value->deposit_amount) > 0){
                                $AR = new AdvanceReceiptController();
                                $AR->increaseDeposit(floatval($insertreceipt->deposit_amount), $insertreceipt->customer_code);
                            }


                        }
                    }


                    DB::commit();
                    Session::flash('success',  "Receipt : $code Succesfully Updated");
                    return json_encode(true);

                } catch (\Throwable $th) {
                    DB::rollBack();
                    throw new \Exception($th->getMessage());
                }



            } else {
                abort(404);
            }

        } else {
            abort(404);
        }

    }


}
