<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PaymentTerm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class InvoiceController extends AdminController
{

    public function getViewInvoice(Request $request){

        $supplyData = [
            'title' => 'Invoice',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),

            ];

        return response()->view("admin.transactions.invoice",$supplyData);
    }

    public function getViewInvoiceManage(Request $request, $code= null){
        $paymentTerm = PaymentTerm::all();
        $data = [
            'paymentTerm' => $paymentTerm
        ];
        if ($code){ //If In Update Mode

            $invoice =  Invoice::join("customers", "invoices.customer_code" , "=", "customers.code")
            ->join("project_realisations", "project_realisations.code", "=", "invoices.project_realisation_code")
            ->join("projects", "project_realisations.project_code", "=", "projects.code")
            ->join("coa", "invoices.coa_revenue", "=", "coa.code")
            ->select('invoices.*','project_realisations.code as project_realisation_code' ,'projects.code as project_code',"coa.name as coa_name", 'projects.name as project_name', 'customers.name as customer_name', 'customers.code as customer_code')
            ->where('invoices.is_approve', 0)
            ->where('invoices.invoice_no', $code)
            ->first();


            if (!$invoice){
                abort(404);
            }


            $data['invoices'] =  $invoice;

        }
        $supplyData = [
            'title' =>$request->route()->getName() == 'admin.addInvoiceView' ?  'Add New Invoice' : 'Edit Invoice',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            'data' => $data
            ];

        return response()->view("admin.transactions.invoicemanage",$supplyData);
    }

    public function approveinvoices($id){
        try {

            DB::beginTransaction();

            Invoice::where("invoice_no", $id)->update(

                [

                    'is_approve' => 1,
                    'approved_by' => Auth::user()->name
                ]
            );


            $journal = new AccountingController();
            $journal->journalInvoices($id);

            DB::commit();
            return response()->redirectToRoute("admin.invoice")->with("success", "Data Invoice $id Successfully Approved");
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorException($th,"admin.invoice", $id );
        }
    }

    public function getTableInvoice(Request $request, DataTables $dataTables){
        if ($request->ajax()){


            $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $paidStatus = intval($request->paystatus) >= 0 ?  $request->paystatus : null;


            $invoice = Invoice::join("customers", "invoices.customer_code" , "=", "customers.code")
            ->join("project_realisations", "project_realisations.code", "=", "invoices.project_realisation_code")
            ->join("projects", "project_realisations.project_code", "=", "projects.code")
            ->select('invoices.*','project_realisations.code as project_realisation_code' ,'projects.code as project_code', 'projects.name as project_name', 'customers.name as customer_name', 'customers.code as customer_code')
            ->whereBetween('invoices.transaction_date', [$startDate,$endDate])
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            })
            ->when($paidStatus !== null , function($query) use($paidStatus){
                    switch (intval($paidStatus)) {
                        case 0:
                            # Unpaid
                            $query->whereRaw("
                            CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(invoices.transaction_date, INTERVAL 30 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
                            WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(invoices.transaction_date, INTERVAL 60 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
                            WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(invoices.transaction_date, INTERVAL 90 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount = 0
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
                            CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(invoices.transaction_date, INTERVAL 30 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                            WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(invoices.transaction_date, INTERVAL 60 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                            WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(invoices.transaction_date, INTERVAL 90 DAY)) <= 0 AND grand_total - paid_amount > 0 AND paid_amount > 0
                            ELSE 0
                            END = 1

                            ");

                            break;
                        case 3:
                            # Overdue
                            $query->whereRaw("CASE WHEN payment_term_code LIKE 'n/30' THEN DATEDIFF(CURDATE() , DATE_ADD(invoices.transaction_date, INTERVAL 30 DAY)) > 0 AND grand_total - paid_amount > 0
                            WHEN payment_term_code LIKE 'n/60' THEN DATEDIFF(CURDATE() , DATE_ADD(invoices.transaction_date, INTERVAL 60 DAY)) > 0 AND grand_total - paid_amount > 0
                            WHEN payment_term_code LIKE 'n/90' THEN DATEDIFF(CURDATE() , DATE_ADD(invoices.transaction_date, INTERVAL 90 DAY)) > 0 AND grand_total - paid_amount > 0
                            ELSE 0
                            END = 1 ");

                            break;

                    }
            });

            return $dataTables->of($invoice)
                ->editColumn('transaction_date', function($row) {
                    return Carbon::parse($row->transaction_date)->format('d/m/Y');
                })
                ->editColumn('grand_total', function($row) {
                    return "Rp " .number_format($row->grand_total,2, ',', '.');
                })
                ->editColumn('total', function($row) {
                    return "Rp " .number_format($row->total,2, ',', '.');
                })
                ->editColumn('percent_ppn', function($row) {
                    return floatval($row->percent_ppn );
                })
                ->editColumn('ppn_amount', function($row) {
                    return "Rp " .number_format($row->ppn_amount,2, ',', '.');
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

                ->filterColumn('project_realisation_code', function($query, $keyword) {
                    $query->whereRaw("project_realisations.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('project_code', function($query, $keyword) {
                    $query->whereRaw("projects.code LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('project_name', function($query, $keyword) {
                    $query->whereRaw("projects.name LIKE ?", ["%{$keyword}%"]);
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
                            <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->invoice_no.'" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->invoice_no.'" title="Delete"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-sm btn-warning approvebtn" data-code="'.$row->invoice_no.'" title="Approve"><i class="fa fa-check"></i></button>
                            <a href="'.route('admin.printdetailinvoice',['id' => $row->invoice_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn" data-code="'.$row->invoice_no.'" title="Print Invoice"><i class="fa fa-print"></i></button></a>
                            </div>';

                            # code...
                            break;
                            case 1: //Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <a href="'.route('admin.printdetailinvoice',['id' => $row->invoice_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn mr-2" data-code="'.$row->invoice_no.'" title="Print Invoice"><i class="fa fa-print"></i></button></a>
                            <a href="'.route('admin.printjurnalinvoice',['id' => $row->invoice_no]).'" target="_blank"><button class="btn btn-sm btn-warning printbtn" data-code="'.$row->invoice_no.'" title="Print Jurnal"><i class="fa fa-print"></i></button></a>
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

    public function deleteinvoices($id){
        try {
            DB::beginTransaction();

            Invoice::where("invoice_no",$id )->delete();
            DB::commit();

            return response()->redirectToRoute("admin.invoice")->with("success", "Data Invoice $id Successfully Deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            // Session::flash('error', $th->getMessage());
            return $this->errorException($th,"admin.invoice", $id );
        }
    }

    public function addinvoice(Request $request){
        if($request->ajax()){

            try {
                //code...
                DB::beginTransaction();
                $data = $request->all();
                $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');

                $payment = Invoice::orderBy("invoice_no", "desc")->lockforUpdate()->first();
                $invno = $this->automaticCode('INV' ,$payment, true,  'invoice_no');
                $data['invoice_no'] =$invno;

                $invoice = new Invoice();
                $invoice->invoice_no =  $data['invoice_no'];
                $invoice->transaction_date =  $data['transaction_date'];
                $invoice->customer_code =  $data['customer_code'];
                $invoice->project_realisation_code =  $data['project_realisation_code'];
                $invoice->bap_no =  $data['bap_no'];
                $invoice->spp_no =  $data['spp_no'];
                $invoice->bapp_no =  $data['bapp_no'];
                $invoice->coa_revenue =  $data['coa_revenue'];
                $invoice->total =  $data['total'];
                $invoice->pph_percent =  0;
                $invoice->pph_amount =  0;
                $invoice->percent_ppn =  floatval($data['percent_ppn']);
                $invoice->ppn_amount =  floatval($data['ppn_amount']);
                $invoice->grand_total =  floatval($data['grand_total']);
                $invoice->paid_amount = 0;
                $invoice->terbilang = "";
                $invoice->description = $data['description'];
                $invoice->is_approve = 0;
                $invoice->approved_by = "";
                $invoice->payment_term_code = $data['payment_term_code'];
                $invoice->created_by = Auth::user()->username;
                $invoice->save();



                DB::commit();
                Session::flash('success',  "New Invoice :  $invoice->invoice_no Succesfully Created");
                return json_encode(true);

            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }

        } else {
            abort(404);
        }
    }

    public function editinvoice($code,Request $request){
        if($request->ajax()){

            try {
                //code...
                DB::beginTransaction();
                $data = $request->all();
                $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');

                $invoice = Invoice::where("invoice_no", $code)->first();
                $invoice->transaction_date =  $data['transaction_date'];
                $invoice->customer_code =  $data['customer_code'];
                $invoice->project_realisation_code =  $data['project_realisation_code'];
                $invoice->bap_no =  $data['bap_no'];
                $invoice->spp_no =  $data['spp_no'];
                $invoice->bapp_no =  $data['bapp_no'];
                $invoice->coa_revenue =  $data['coa_revenue'];
                $invoice->total =  $data['total'];
                $invoice->pph_percent =  0;
                $invoice->pph_amount =  0;
                $invoice->percent_ppn =  floatval($data['percent_ppn']);
                $invoice->ppn_amount =  floatval($data['ppn_amount']);
                $invoice->grand_total =  floatval($data['grand_total']);
                $invoice->paid_amount = 0;
                $invoice->terbilang = "";
                $invoice->description = $data['description'];
                $invoice->is_approve = 0;
                $invoice->approved_by = "";
                $invoice->payment_term_code = $data['payment_term_code'];
                $invoice->updated_by = Auth::user()->username;
                $invoice->update();



                DB::commit();
                Session::flash('success',  "Invoice : $code Succesfully Updated");
                return json_encode(true);

            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }

        } else {
            abort(404);
        }
    }

    public function printjurnalinvoice($id){
        $printcontroller = new PrintController();
        return $printcontroller->printjurnalinvoice($id);
    }

    public function printdetailinvoice($id){
        $printcontroller = new PrintController();
        return $printcontroller->printdetailinvoice($id);
    }

    public function printrecapinvoice(Request $request){
        $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
        $startDate = $request->startDate;
        $endDate =  $request->endDate;
        $paidStatus = intval($request->paystatus) >= 0 ?  $request->paystatus : null;
        $customercode = $request->customercode;

        $printcontroller = new PrintController();
        return $printcontroller->printrecapinvoice($customercode,$startDate, $endDate, $is_approve, $paidStatus);
    }

}
