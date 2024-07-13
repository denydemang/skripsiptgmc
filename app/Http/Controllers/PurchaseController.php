<?php

namespace App\Http\Controllers;

use App\Models\PaymentTerm;
use App\Models\Purchase;
use App\Models\Purchase_Detail;
use App\Models\Purchase_Request;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class PurchaseController extends AdminController
{
        public function getViewPurchase(Request $request){

            $supplyData = [
                'title' => 'Purchase',
                'users' => Auth::user(),
                'sessionRoute' =>  $request->route()->getName(),
        
                ];
    
            return response()->view("admin.transactions.purchase",$supplyData);
        }

        public function getViewPurchaseManage(Request $request, $code=null){

            $paymentTerm = PaymentTerm::all();
            $data = [
                'paymentTerm' => $paymentTerm
            ];
            if ($code){ //If In Update Mode
    
                $purchase = Purchase::where("purchase_no", $code)
                ->where("is_approve" , 0)
                ->join("suppliers", "purchases.supplier_code", "=", "suppliers.code")
                ->select('purchases.*', 'suppliers.code as supplier_code', 'suppliers.name as supplier_name')
                ->first();
            
                if (!$purchase){
                    abort(404);
                }

                $purchase_detail = Purchase_Detail::where("purchase_no", $code)
                ->join("items", 'purchase_details.item_code', '=', 'items.code')
                ->get();
                $data['purchases'] =  $purchase;
                $data['purchase_detail'] =  json_encode($purchase_detail);
                    
            }
            $supplyData = [
                'title' =>$request->route()->getName() == 'admin.addPurchaseView' ?  'Add New Purchase' : 'Edit Purchase',
                'users' => Auth::user(),
                'sessionRoute' =>  $request->route()->getName(),
                'data' => $data
                ];
    
            return response()->view("admin.transactions.purchasemanage",$supplyData);
        }

        public function getTablePurchase(Request $request, DataTables $dataTables ){
            if ($request->ajax()){

        
                $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
                $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
                $paidStatus = intval($request->paystatus) >= 0 ?  $request->paystatus : null;    
                
                
                $purchase = Purchase::join("suppliers", "purchases.supplier_code" , "=", "suppliers.code")
                ->select('purchases.*', DB::raw('ifnull(suppliers.name ,"-" ) as supplier_name'),  DB::raw('ifnull(suppliers.address ,"-" ) as supplier_address'), DB::raw('ifnull(suppliers.phone,"-" ) as supplier_phone'))
                ->whereBetween('transaction_date', [$startDate,$endDate])
                ->when($is_approve !== null , function($query) use($is_approve){
                    $query->where('is_approve', $is_approve);
                })
                ->when($paidStatus !== null , function($query) use($paidStatus){
                        switch (intval($paidStatus)) {
                            case 0:
                                # Unpaid
                                $query->whereRaw("
                                CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 30 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0 
                                WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 60 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
                                WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 90 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
                                ELSE 0
                                END = 1
                                
                                ");

                                break;
                            case 1:
                                # Fullpaid
                                $query->whereRaw("grand_total - paid_amount <= 0");

                                break;
                            case 2:
                                # Outstanding
                                $query->whereRaw("
                                CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 30 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0 
                                WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 60 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                                WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 90 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                                ELSE 0
                                END = 1
                                
                                ");

                                break;
                            case 3:
                                # Overdue
                                $query->whereRaw("CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 30 DAY)) > 0 AND grand_total - paid_amount > 0 
                                WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 60 DAY)) > 0 AND grand_total - paid_amount > 0 
                                WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(transaction_date, INTERVAL 90 DAY)) > 0 AND grand_total - paid_amount > 0 
                                ELSE 0
                                END = 1 ");

                                break;
                            
                        }
                });

                return $dataTables->of($purchase)
                    ->editColumn('transaction_date', function($row) {
                        return Carbon::parse($row->transaction_date)->format('d/m/Y');
                    })
                    ->editColumn('total', function($row) {
                        return "Rp " .number_format($row->total,2, ',', '.');
                    })
                    ->editColumn('other_fee', function($row) {
                        return "Rp " .number_format($row->other_fee,2, ',', '.');
                    })
                    ->editColumn('percent_ppn', function($row) {
                        return floatval($row->percen_ppn);
                    })
                    ->editColumn('ppn_amount', function($row) {
                        return "Rp " .number_format($row->ppn_amount,2, ',', '.');
                    })
                    ->editColumn('grand_total', function($row) {
                        return "Rp " .number_format($row->grand_total,2, ',', '.');
                    })
                    ->editColumn('paid_amount', function($row) {
                        return "Rp " .number_format($row->paid_amount,2, ',', '.');
                    })
                    ->addColumn('not_paid_amount', function ($row) {
                        return "Rp " .number_format($row->grand_total - $row->paid_amount ,2, ',', '.');
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
                                <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->purchase_no.'" title="Edit"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->purchase_no.'" title="Delete"><i class="fa fa-trash"></i></button>
                                <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->purchase_no.'" title="View Detail"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-sm btn-warning approvebtn" data-code="'.$row->purchase_no.'" title="Approve"><i class="fa fa-check"></i></button>
                                <a href="'.route('admin.printdetailpurchase',['id' => $row->purchase_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn" data-code="'.$row->purchase_no.'" title="Print Purchase"><i class="fa fa-print"></i></button></a>
                                </div>';
                                
                                # code...
                                break;
                                case 1: //Approved
                                $html = '
                                <div class="d-flex justify-content-center">
                                <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->purchase_no.'" title="View Detail"><i class="fa fa-eye"></i></button>
                                <a href="'.route('admin.printdetailpurchase',['id' => $row->purchase_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn mr-2" data-code="'.$row->purchase_no.'" title="Print Purchase"><i class="fa fa-print"></i></button></a>
                                <a href="'.route('admin.printjurnalpurchase',['id' => $row->purchase_no]).'" target="_blank"><button class="btn btn-sm btn-warning printbtn" data-code="'.$row->purchase_no.'" title="Print Jurnal"><i class="fa fa-print"></i></button></a>
                                </div>';
                                
                                break;
                            
                            default:
                                # code...
                                break;
                        }
        
                        return $html;
                    })
                    ->addColumn('due_date', function ($row) {
                        switch ($row->payment_term_code) {
                            case 'n/30':
                                return Carbon::parse($row->transaction_date)->addDays(30)->format('d/m/Y');
                                break;
                            case 'n/60':
                                return Carbon::parse($row->transaction_date)->addDays(60)->format('d/m/Y');
                                break;
                            case 'n/90':
                                return Carbon::parse($row->transaction_date)->addDays(90)->format('d/m/Y');
                                break;
                        }
                    })
                    ->addColumn('paid_status', function ($row) {
                        $html = '';
                        if(floatval($row->grand_total) - floatval($row->paid_amount) <= 0){
                            // Lunas
                            $html= "<span class='badge badge-success'>Full Paid</span>";

                        }
                        else {

                            if ($this->isOutOfTermin($row->payment_term_code, $row->transaction_date)){

                                $html= "<span class='badge badge-danger'>OverDue</span>";

                            } else{

                                if (floatval($row->paid_amount) == 0){
                                    // Belum Bayar
                                    $html= "<span class='badge badge-warning'>UnPaid</span>";
                                } else {
                                    // Belum Lunas
                                    $html= "<span class='badge badge-primary'>Outstanding</span>";
                                }
                            }
                        }
        
                        return $html;
                    })
                    ->rawColumns(['action','is_approve' , 'paid_status'])
                    ->addIndexColumn()
                    ->make(true);
                    
            } else {
                abort(404);
            }
            
        }

        public function deletepurchase($id){
            try {
                DB::beginTransaction();
                $purchase = Purchase::where("purchase_no",$id )->first();
                Purchase_Request::where("pr_no", $purchase->pr_no)->update([
                    "is_purchased" => 0
                ]);

                Stock::where("ref_no", $id)->delete();
                Purchase::where("purchase_no",$id )->delete();
                DB::commit();
                
                return response()->redirectToRoute("admin.purchase")->with("success", "Data Purchase $id Successfully Deleted");
            } catch (\Throwable $th) {
                DB::rollBack();
                // Session::flash('error', $th->getMessage());
                return $this->errorException($th,"admin.purchase", $id );
            }
        }

        public function approvepurchase($id){
                try {

                    DB::beginTransaction();

                    Purchase::where("purchase_no", $id)->update(
                        
                        [
                            
                            'is_approve' => 1,
                            'approved_by' => Auth::user()->name
                        ]
                    );

                    $journal = new AccountingController();
                    $journal->journalPembelian($id);
                
                    DB::commit();
                    return response()->redirectToRoute("admin.purchase")->with("success", "Data Purchase $id Successfully Approved");
                } catch (\Throwable $th) {
                    DB::rollBack();

                    return $this->errorException($th,"admin.purchase", $id );
                }

        }

        public function detailpurchase($id,  Request $request){
            if ($request->ajax()){
    
                $dataDetail  = Purchase_Detail::join("items", "purchase_details.item_code", "=", "items.code")
                ->where("purchase_details.purchase_no", $id)->get();
    
                return json_encode($dataDetail);
    
            } else {
                abort(404);
            }
        }

        public function printdetailpurchase($id){
            $printcontroller = new PrintController();
            return $printcontroller->printpurchasedetail($id);
        }

        public function printjurnalpurchase($id){
    
            $printcontroller = new PrintController();
            return $printcontroller->printjurnalpurchase($id);
        }

        public function printrecappurchase(Request $request){
            $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
            $startDate = $request->startDate;
            $endDate =  $request->endDate;
            $paidStatus = intval($request->paystatus) >= 0 ?  $request->paystatus : null;   
            $suppliercode = $request->suppliercode;

            $printcontroller = new PrintController();
            return $printcontroller->printrecappurchase($suppliercode,$startDate, $endDate, $is_approve, $paidStatus);
        }


        public function addPurchase(Request $request ){
            if($request->ajax()){
    
                try {
                    //code...
                    DB::beginTransaction();
                    $purchase = Purchase::orderBy("purchase_no", "desc")->lockforUpdate()->first();
                    $data = $request->all();
                    $purchase_no = $this->automaticCode('PRC' ,$purchase, true,  'purchase_no');
        
                    $datapurchasedetails = json_decode($data['purchase_detail']);
                    $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');
        
                    $data['purchase_no'] =  $purchase_no;
    
                    $otherfee = floatval($data['other_fee']) / count($datapurchasedetails);
                    foreach($datapurchasedetails as $i){
                        $stock = new StockController(); 
                        $cogs = bcdiv((floatval($i->sub_total) + $otherfee) , floatval($i->qty), 4);
                        
                        $stock->stockin($purchase_no, $i->item_code, $i->unit_code, $data['transaction_date'], floatval($i->qty), $cogs,2);
                    }
    
                    $purchase = new purchase();
                    $purchase->purchase_no = $data['purchase_no'];
                    $purchase->pr_no = $data['pr_no'];
                    $purchase->transaction_date = $data['transaction_date'];
                    $purchase->supplier_code = $data['supplier_code'];
                    $purchase->total = floatval($data['total']);
                    $purchase->other_fee = floatval($data['other_fee']);
                    $purchase->percen_ppn = floatval($data['percen_ppn']);
                    $purchase->ppn_amount = floatval($data['ppn_amount']);
                    $purchase->grand_total = floatval($data['grand_total']);
                    $purchase->payment_term_code = $data['payment_term_code'];
                    $purchase->is_credit = 1;
                    $purchase->is_approve = 0;
                    $purchase->paid_amount = 0;
                    $purchase->description = $data['description'];
                    $purchase->created_by = Auth::user()->name;
                    $purchase->save();

                    Purchase_Request::where("pr_no", $purchase->pr_no)->update([
                        "is_purchased" => 1
                    ]);
    
                    foreach($datapurchasedetails as $i){
                        $purchase_details= new purchase_Detail();
                        $purchase_details->purchase_no = $purchase->purchase_no;
                        $purchase_details->item_code = $i->item_code;
                        $purchase_details->unit_code = $i->unit_code;
                        $purchase_details->qty = floatval($i->qty);
                        $purchase_details->price = floatval($i->price);

                        $purchase_details->total = floatval($i->total);
                        $purchase_details->discount = floatval($i->discount);
                        $purchase_details->sub_total = floatval($i->sub_total);
                        $purchase_details->created_by = Auth::user()->name;
                        $purchase_details->save();
                    }

    
                    DB::commit();
                    Session::flash('success',  "New purchase : $purchase->purchase_no Succesfully Created");
                    return json_encode(true);
                    
                } catch (\Throwable $th) {
                    DB::rollBack();
                    throw new \Exception($th->getMessage());
                }
    
    
    
            } else {
                abort(404);
            }

        }

        public function editPurchase(Request $request , $code ){
            if($request->ajax()){
    
                try {
                    //code...
                    DB::beginTransaction();
                    $data = $request->all();        
                    $datapurchasedetails = json_decode($data['purchase_detail']);
                    $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');
        
                    Stock::where('ref_no', $code)->delete();
                    $otherfee = floatval($data['other_fee']) / count($datapurchasedetails);
                    foreach($datapurchasedetails as $i){
                        $stock = new StockController();
                        $cogs = (floatval($i->sub_total) + $otherfee) / floatval($i->qty);
                        $stock->stockin($code, $i->item_code, $i->unit_code, $data['transaction_date'], floatval($i->qty), floatval($cogs));
                    }
    
                    $purchase = Purchase::where("purchase_no", $code)->first();
                    $purchase->pr_no = $data['pr_no'];
                    $purchase->transaction_date = $data['transaction_date'];
                    $purchase->supplier_code = $data['supplier_code'];
                    $purchase->total = floatval($data['total']);
                    $purchase->other_fee = floatval($data['other_fee']);
                    $purchase->percen_ppn = floatval($data['percen_ppn']);
                    $purchase->ppn_amount = floatval($data['ppn_amount']);
                    $purchase->grand_total = floatval($data['grand_total']);
                    $purchase->payment_term_code = $data['payment_term_code'];
                    $purchase->is_credit = 1;
                    $purchase->is_approve = 0;
                    $purchase->paid_amount = 0;
                    $purchase->description = $data['description'];
                    $purchase->updated_by = Auth::user()->name;
                    $purchase->update();


                    Purchase_Detail::where('purchase_no', $code)->delete();
                    foreach($datapurchasedetails as $i){
                        $purchase_details= new purchase_Detail();
                        $purchase_details->purchase_no = $purchase->purchase_no;
                        $purchase_details->item_code = $i->item_code;
                        $purchase_details->unit_code = $i->unit_code;
                        $purchase_details->qty = $i->qty;
                        $purchase_details->price = $i->price;

                        $purchase_details->total = $i->total;
                        $purchase_details->discount = $i->discount;
                        $purchase_details->sub_total = $i->sub_total;
                        $purchase_details->created_by = Auth::user()->name;
                        $purchase_details->save();
                    }

    
                    DB::commit();
                    Session::flash('success',  "Purchase : $code Succesfully Updated");
                    return json_encode(true);
                    
                } catch (\Throwable $th) {
                    DB::rollBack();
                    // throw new \Exception($th->getMessage());
                    $this->errorException2($th, $code );
                }
    
    
    
            } else {
                abort(404);
            }

        }

}
