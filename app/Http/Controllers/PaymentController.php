<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use Carbon\Carbon;
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
            return response()->redirectToRoute("admin.payment")->with("error", $th->getMessage());
            return $this->errorException($th,"admin.payment", $id );
        }
    }

    public function approvepayment($id){
        try {

            DB::beginTransaction();

            Payment::where("bkk_no", $id)->update(   
                [
                    
                    'is_approve' => 1,
                    'approved_by' => Auth::user()->name
                ]
            );

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
}
