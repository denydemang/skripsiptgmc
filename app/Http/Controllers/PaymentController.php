<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use App\Services\CheckBalanceCOAService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class PaymentController extends AdminController
{
    public function getViewPayment(Request $request){

        $supplyData = [
            'title' => 'Payment',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            ];

        return response()->view("admin.finance.payment",$supplyData);
    }
    public function getViewPaymentManage(Request $request, $code=null){

        $data= [];
        if ($code){ //If In Update Mode

            $payment = Payment::where("bkk_no", $code)
            ->where("is_approve" , 0)
            ->join("suppliers", "payments.supplier_code", "=", "suppliers.code")
            ->select('payments.*', 'suppliers.code as supplier_code', 'suppliers.name as supplier_name')
            ->first();

            if (!$payment){
                abort(404);
            }

            $subQuery = DB::table('purchases as p')
            ->select(
                'p.purchase_no',
                'p.transaction_date',
                'p.supplier_code',
                'p.is_approve',
                DB::raw("
                    CASE
                        WHEN p.payment_term_code LIKE 'n/30' THEN DATE_ADD(p.transaction_date, INTERVAL 30 DAY)
                        WHEN p.payment_term_code LIKE 'n/60' THEN DATE_ADD(p.transaction_date, INTERVAL 60 DAY)
                        WHEN p.payment_term_code LIKE 'n/90' THEN DATE_ADD(p.transaction_date, INTERVAL 90 DAY)
                    END AS due_date
                "),
                DB::raw('(p.grand_total - p.paid_amount) AS balance')
            );

            // Use the subquery in the main query
            $results = DB::table(DB::raw("({$subQuery->toSql()}) as qry"))
            ->mergeBindings($subQuery)
            ->select('qry.purchase_no','qry.supplier_code', 'qry.transaction_date','qry.is_approve', 'qry.due_date', 'qry.balance')
            ->where("qry.purchase_no", "=", $payment->ref_no)
            // ->where("qry.due_date", ">=" , DB::raw("CURDATE()"))
            ->first();

        $data['payment'] = $payment;
        $data['detail'] = json_encode($results);
        }
        $supplyData = [
            'title' =>$request->route()->getName() == 'admin.addPaymentView' ?  'Add New Payment' : 'Edit Payment',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            'data' => $data
            ];

        return response()->view("admin.finance.paymentmanage",$supplyData);
    }


    public function getTablePayment(Request $request, DataTables $dataTables ){
        if ($request->ajax()){


            $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');


            $payment = Payment::join("suppliers", "payments.supplier_code" , "=", "suppliers.code")
            ->select('payments.*', DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),  DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'), DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'))
            ->whereBetween('transaction_date', [$startDate,$endDate])
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            });

            return $dataTables->of($payment)
                ->editColumn('transaction_date', function($row) {
                    return Carbon::parse($row->transaction_date)->format('d/m/Y');
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

                ->filterColumn('supplier_name', function($query, $keyword) {
                    $query->whereRaw("suppliers.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('supplier_address', function($query, $keyword) {
                    $query->whereRaw("suppliers.address LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('supplier_phone', function($query, $keyword) {
                    $query->whereRaw("suppliers.phone LIKE ?", ["%{$keyword}%"]);
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    switch ($row->is_approve) {
                        case 0: //Not Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->bkk_no.'" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->bkk_no.'" title="Delete"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-sm btn-warning approvebtn" data-code="'.$row->bkk_no.'" title="Approve"><i class="fa fa-check"></i></button>
                            <a href="'.route('admin.printdetailpayment',['id' => $row->bkk_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn" data-code="'.$row->bkk_no.'" title="Print payment"><i class="fa fa-print"></i></button></a>
                            </div>';

                            # code...
                            break;
                            case 1: //Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <a href="'.route('admin.printdetailpayment',['id' => $row->bkk_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn mr-2" data-code="'.$row->bkk_no.'" title="Print payment"><i class="fa fa-print"></i></button></a>
                            <a href="'.route('admin.printjurnalpayment',['id' => $row->bkk_no]).'" target="_blank"><button class="btn btn-sm btn-warning printbtn" data-code="'.$row->bkk_no.'" title="Print Jurnal"><i class="fa fa-print"></i></button></a>
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

    public function deletepayment($id){
        try {
            DB::beginTransaction();
            $payment = Payment::where("bkk_no",$id )->first();
            $purchase = Purchase::where("purchase_no",$payment->ref_no)->first();
            $purchase->paid_amount = $purchase->paid_amount - $payment->total_amount;
            $purchase->update();
            $payment->delete();
            DB::commit();

            return response()->redirectToRoute("admin.payment")->with("success", "Data Payment $id Successfully Deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            // Session::flash('error', $th->getMessage());
            return $this->errorException($th,"admin.payment", $id );
        }
    }

    public function approvepayment($id, CheckBalanceCOAService $check){
        try {

            DB::beginTransaction();

            Payment::where("bkk_no", $id)->update(
                [

                    'is_approve' => 1,
                    'approved_by' => Auth::user()->name
                ]
            );



            $coaCash = Payment::where("bkk_no", $id)->first()->coa_cash_code;
            $amount = floatval(Payment::where("bkk_no", $id)->first()->total_amount);


            // dd($coaCash);

            $journal = new AccountingController();
            $journal->journalPembayaran($id);

            DB::commit();
            return response()->redirectToRoute("admin.payment")->with("success", "Data Payment $id Successfully Approved");
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorException($th,"admin.payment", $id );
        }
    }

    public function printjurnalpayment($id){
        $printcontroller = new PrintController();
        return $printcontroller->printjurnalpayment($id);
    }

    public function printdetailpayment($id){
        $printcontroller = new PrintController();
        return $printcontroller->printdetailpayment($id);
    }

    public function printrecappayment(Request $request){
        $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
        $startDate = $request->startDate;
        $endDate =  $request->endDate;
        $suppliercode = $request->suppliercode;

        $printcontroller = new PrintController();
        return $printcontroller->printrecappayment($suppliercode,$startDate, $endDate, $is_approve);
    }

    public function getpurchaseforpayment($code, Request $request){

        if ($request->ajax()){

    // Create the subquery
            $subQuery = DB::table('purchases as p')
            ->select(
                'p.purchase_no',
                'p.transaction_date',
                'p.supplier_code',
                'p.is_approve',
                DB::raw("
                    CASE
                        WHEN p.payment_term_code LIKE 'n/30' THEN DATE_ADD(p.transaction_date, INTERVAL 30 DAY)
                        WHEN p.payment_term_code LIKE 'n/60' THEN DATE_ADD(p.transaction_date, INTERVAL 60 DAY)
                        WHEN p.payment_term_code LIKE 'n/90' THEN DATE_ADD(p.transaction_date, INTERVAL 90 DAY)
                    END AS due_date
                "),
                DB::raw('(p.grand_total - p.paid_amount) AS balance')
            );

            // Use the subquery in the main query
            $results = DB::table(DB::raw("({$subQuery->toSql()}) as qry"))
            ->mergeBindings($subQuery)
            ->select('qry.purchase_no','qry.supplier_code', 'qry.transaction_date','qry.is_approve', 'qry.due_date', 'qry.balance')
            ->where("qry.supplier_code", "=", $code)
            ->where('qry.balance' , ">", 0)
            ->where("qry.due_date", ">=" , DB::raw("CURDATE()"))
            ->where("qry.is_approve", "=" , 1)
            ->get();

            return response()->json($results);

        } else {
            abort(404);
        }
    }


    public function addPayment(Request $request ){
        if($request->ajax()){


            try {
                //code...
                DB::beginTransaction();
                $data = $request->all();

                $details= json_decode($data['detail']);
                $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');
                $listBKKNo = [];
                foreach ($details as $key => $value) {

                    if (floatval($value->paid_amount) > 0 ){

                        $payment = Payment::orderBy("bkk_no", "desc")->lockforUpdate()->first();
                        $bkkNo = $this->automaticCode('BKK' ,$payment, true,  'bkk_no');
                        $insertPayment = new Payment();
                        $insertPayment->transaction_date =  $data['transaction_date'];
                        $insertPayment->bkk_no = $bkkNo;
                        $insertPayment->supplier_code = $data['supplier_code'];
                        $insertPayment->ref_no = $value->ref_no;
                        $insertPayment->coa_cash_code = $data['coa_cash_code'];
                        $insertPayment->total_amount = floatval($value->paid_amount);
                        $insertPayment->payment_method = $data['payment_method'];
                        $insertPayment->description = $data['description'];
                        $insertPayment->terbilang = $this->terbilang(floatval($value->paid_amount));
                        $insertPayment->created_by = Auth::user()->name;
                        $insertPayment->approved_by = "";
                        $insertPayment->save();

                        $purchase = Purchase::where("purchase_no" , $value->ref_no)->first();

                        $purchase->paid_amount = $purchase->paid_amount + floatval($value->paid_amount);
                        $purchase->update();

                        array_push($listBKKNo,$bkkNo);

                    }
                }

                $commaSeparated = implode(', ', $listBKKNo);
                DB::commit();
                Session::flash('success',  "New Payment : $commaSeparated Succesfully Created");
                return json_encode(true);

            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }



        } else {
            abort(404);
        }

    }

    public function editpayment($code ,Request $request ){
        if($request->ajax()){


            try {
                //code...
                DB::beginTransaction();
                $data = $request->all();

                $details= json_decode($data['detail']);
                $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');
                $listBKKNo = [];
                foreach ($details as $key => $value) {

                    if (floatval($value->paid_amount) > 0 ){

                        $updatePayment = Payment::where("bkk_no", $code)->first();

                        $purchase = Purchase::where("purchase_no" , $value->ref_no)->first();
                        $purchase->paid_amount = floatval($purchase->paid_amount) - floatval($updatePayment->total_amount);
                        $purchase->update();

                        $updatePayment->transaction_date =  $data['transaction_date'];
                        $updatePayment->supplier_code = $data['supplier_code'];
                        $updatePayment->ref_no = $value->ref_no;
                        $updatePayment->coa_cash_code = $data['coa_cash_code'];
                        $updatePayment->total_amount = floatval($value->paid_amount);
                        $updatePayment->payment_method = $data['payment_method'];
                        $updatePayment->description = $data['description'];
                        $updatePayment->terbilang = $this->terbilang(floatval($value->paid_amount));
                        $updatePayment->updated_by = Auth::user()->name;
                        $updatePayment->approved_by = "";
                        $updatePayment->update();

                        $purchase->paid_amount = $purchase->paid_amount + floatval($updatePayment->total_amount);
                        $purchase->update();

                    }
                }

                DB::commit();
                Session::flash('success',  "Payment : $code Succesfully Updated");
                return json_encode(true);

            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }



        } else {
            abort(404);
        }

    }
}
