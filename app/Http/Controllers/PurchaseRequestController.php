<?php

namespace App\Http\Controllers;

use App\Models\Purchase_Detail;
use App\Models\Purchase_Request;
use App\Models\PurchaseRequest_Detail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class PurchaseRequestController extends AdminController
{
    public function getViewPR(Request $request){
        $supplyData = [
            'title' => 'Purchase Request',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),

            ];

        return response()->view("admin.inventory.purchaserequest",$supplyData);

    }

    public function getDataPRForModal(Request $request, DataTables $dataTables){
        if ($request->ajax()){


            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $pr = Purchase_Request::where("is_approve", 1)->where('is_purchased', 0)
            ->whereBetween('transaction_date', [$startDate,$endDate]);

            return $dataTables->of($pr)
            ->editColumn('transaction_date', function($row) {
                return $row->transaction_date ? Carbon::parse($row->transaction_date)->format('d/m/Y') : '';
            })
            ->editColumn('date_need', function($row) {
                return $row->date_need ? Carbon::parse($row->date_need)->format('d/m/Y') : '';
            })
            ->addColumn('action', function ($row) {

                return '
                <div class="d-flex justify-content-center">
                    <button class="btn btn-sm btn-success selectprbutton" data-code="'.$row->pr_no.'" title="Select"><i class="fa fa-check"></i> Select</button>
                </div>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);

        }
    }

    public function getViewPRManage(Request $request, $code=null){
        $data = [];
        if ($code){ //If In Update Mode
        
            $pr = Purchase_Request::where("pr_no", $code)->first();
            
            if (!$pr){
                abort(404);
            }

            $pr_detail = PurchaseRequest_Detail::leftJoin("items",'purchase_request_details.item_code', '=', 'items.code')
            ->leftJoin("units" ,'purchase_request_details.unit_code', '=', 'units.code' )
            ->leftjoin("stocks", 'purchase_request_details.item_code', '=' , 'stocks.item_code')
            ->select("purchase_request_details.pr_no",
            "items.code as item_code", "items.name as item_name",
            "units.code as unit_code", DB::raw('IFNULL(SUM(stocks.actual_stock - stocks.used_stock), 0) As stocks'),"purchase_request_details.qty" , "purchase_request_details.created_by",
            "purchase_request_details.updated_by" , "purchase_request_details.created_at", "purchase_request_details.updated_at"  )
            ->groupBy('purchase_request_details.pr_no', 'items.code', 'items.name', 'units.code', 'purchase_request_details.qty', "purchase_request_details.created_by",  "purchase_request_details.updated_by", "purchase_request_details.created_at" , "purchase_request_details.updated_at")
            ->where("purchase_request_details.pr_no", $code)
            ->get();

            $data = [
                "dataPR" => $pr,
                "dataDetail" => json_encode($pr_detail)
            ];


        }

        $supplyData = [
            'title' =>$request->route()->getName() == 'admin.addprview' ?  'Add New Purchase Request' : 'Edit Purchase Request',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            'data' => $data
        ];
    
        return response()->view("admin.inventory.purchaserequestmanage",$supplyData);
    }

    public function getTablePR(Request $request, DataTables $dataTables){

    
        if ($request->ajax()){


        
            $is_approve = intval($request->is_approve) >=  0  ? $request->is_approve : null ;
            $is_purchased = intval($request->is_purchased) >=  0  ? $request->is_purchased : null ;
            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $dateNeedStart = $request->dateNeedStart ? Carbon::createFromFormat('d/m/Y', $request->dateNeedStart)->format('Y-m-d') :null;
            $dateNeedEnd = $request->dateNeedEnd ? Carbon::createFromFormat('d/m/Y',  $request->dateNeedEnd)->format('Y-m-d')  : null;
        
            
            $pr = Purchase_Request::whereBetween('transaction_date', [$startDate,$endDate])
            ->when($is_approve !== null , function($query) use($is_approve){
                $query->where('is_approve', $is_approve);
            })
            ->when($is_purchased !== null ,function($query) use($is_purchased) {
                $query->where('is_purchased', $is_purchased);
            })
            ->when($dateNeedStart !== null && $dateNeedEnd  ,function($query) use($dateNeedStart,$dateNeedEnd) {
                $query->whereBetween('date_need', [$dateNeedStart, $dateNeedEnd]);
            });
            return $dataTables->of($pr)
                ->editColumn('transaction_date', function($row) {
                    return Carbon::parse($row->transaction_date)->format('d/m/Y');
                })
                ->editColumn('date_need', function($row) {
                        return $row->date_need ? Carbon::parse($row->date_need)->format('d/m/Y') : '';
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
                ->editColumn('is_purchased', function($row) {
                    $html ="";
                    $html ="";
                    switch ($row->is_purchased) {
                        case 0:
                            $html= "</div> <span class='badge badge-warning'>Not Purchased</span>";
                            break;
                        case 1:
                            $html= "<span class='badge badge-primary'>Already Purchased</span>";
                            break;
                    }
                    return $html;
                })
                // ->setRowAttr([
                //     'style' => function($item){
                //     $date_need =  Carbon::parse($item->date_need);
                //     $dateNow = Carbon::now();

                //     // Menghitung perbedaan antara tanggal sekarang dan tanggal dateneed
                //     $diff = $dateNow->diffInDays($date_need);

                //         $style = '';
                //         if ($diff <= 1 && intval($item->is_purchased) == 0 ){
                //             $style = 'background-color: #f56262; color:white';
                //         } else {
                //             $style="";
                //         }
                //         return  $style;
                //     }
                // ])
                ->addColumn('action', function ($row) {
                    $html = '';
                    switch ($row->is_approve) {
                        case 0: //Not Started
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->pr_no.'" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->pr_no.'" title="Delete"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->pr_no.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning approvebtn" data-code="'.$row->pr_no.'" title="Approve"><i class="fa fa-check"></i></button>
                            <a href="'.route('admin.printPR',['id' => $row->pr_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn" data-code="'.$row->pr_no.'" title="Print"><i class="fa fa-print"></i></button></a>
                            </div>';
                            
                            # code...
                            break;
                            case 1: //Started
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->pr_no.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            <a href="'.route('admin.printPR',['id' => $row->pr_no]).'" target="_blank"><button class="btn btn-sm btn-info printbtn" data-code="'.$row->pr_no.'" title="Print"><i class="fa fa-print"></i></button></a>
                            </div>';
                            
                            break;
                        
                        default:
                            # code...
                            break;
                    }
    
                    return $html;
                })
                ->rawColumns(['action','is_approve','is_purchased' ])
                ->addIndexColumn()
                ->make(true);
                
     
        } else {
            abort(404);
        }
        
    }

    public function addPR(Request $request){
    
        if($request->ajax()){

            try {
            
                $data = $request->all();

                DB::beginTransaction();
                $pr = Purchase_Request::orderBy("pr_no", "desc")->lockforUpdate()->first();
                $pr_no = $this->automaticCode('PR' ,$pr, true,  'pr_no');
            

                $pr_details = json_decode($data['pr_details']);

                $pr = new Purchase_Request();
                $pr->pr_no = $pr_no;
                $pr->transaction_date =  Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');
                $pr->pic_name = htmlspecialchars($data['pic_name']);
                $pr->division = htmlspecialchars($data['division']);
                $pr->ref_no = htmlspecialchars($data['ref_no']) ;
                $pr->description = htmlspecialchars($data['description']) ;
                $pr->is_approve = 0;
                $pr->is_purchased = 0;
                $pr->approved_by = null;
                $pr->date_need = Carbon::createFromFormat('d/m/Y',$data['date_need'])->format('Y-m-d');
                $pr->created_by = Auth::user()->name;
                $pr->save();

                foreach($pr_details as $i){
                    $pr_details= new PurchaseRequest_Detail();
                    $pr_details->pr_no = $pr->pr_no;
                    $pr_details->item_code = $i->item_code;
                    $pr_details->unit_code = $i->unit_code;
                    $pr_details->qty = $i->qty;
                    $pr_details->created_by = Auth::user()->name;
                    $pr_details->save();
                }
                DB::commit();
                Session::flash('success',  "New Purchase Request : $pr->pr_no Succesfully Created");
                return json_encode(true);
                
            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }



        } else {
            abort(404);
        }

    }

    public function editPR(Request $request, $code){
        if ($request->ajax()){
            try {
            
                $data = $request->all();

                
                DB::beginTransaction();
            
                $pr_details = json_decode($data['pr_details']);
        

                $pr = Purchase_Request::where("pr_no", $code)->first();
                $pr->transaction_date =  Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');
                $pr->pic_name = htmlspecialchars($data['pic_name']);
                $pr->division = htmlspecialchars($data['division']);
                $pr->ref_no = htmlspecialchars($data['ref_no']) ;
                $pr->description = htmlspecialchars($data['description']) ;
                $pr->is_approve = 0;
                $pr->is_purchased = 0;
                $pr->approved_by = null;
                $pr->date_need = Carbon::createFromFormat('d/m/Y',$data['date_need'])->format('Y-m-d');
                $pr->updated_by = Auth::user()->name;
                $pr->update();

                PurchaseRequest_Detail::where("pr_no", $code)->delete();
                foreach($pr_details as $i){
                    $pr_details= new PurchaseRequest_Detail();
                    $pr_details->pr_no = $pr->pr_no;
                    $pr_details->item_code = $i->item_code;
                    $pr_details->unit_code = $i->unit_code;
                    $pr_details->qty = $i->qty;
                    $pr_details->created_by = Auth::user()->name;
                    $pr_details->updated_by = Auth::user()->name;
                    $pr_details->save();
                }
                DB::commit();
                Session::flash('success',  "Purchase Request : $pr->pr_no Succesfully Updated");
                return json_encode(true);
                
            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }

        } else {
            abort(404);
        }
    }
    public function deletePR($code){
        try {
            Purchase_Request::where("pr_no", $code)->delete();
            return response()->redirectToRoute("admin.pr")->with("success", "Data $code Successfully Deleted");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorException($th,"admin.pr", $code );
        }
    }
    public function approvePR($code){
        try {
            $pr =  Purchase_Request::where("pr_no", $code)->first();
            $pr->is_approve = 1;
            $pr->approved_by = Auth::user()->name;
            $pr->update();
        
            return response()->redirectToRoute("admin.pr")->with("success", "Data $code Successfully Approved");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorException($th,"admin.pr", $code );
        }
    }
    public function detailPR($code, Request $request){
        if ($request->ajax()){
    
            $dataDetail  = PurchaseRequest_Detail::join("items", "purchase_request_details.item_code", "=", "items.code")
            ->where("purchase_request_details.pr_no", $code)->get();

            return json_encode($dataDetail);

        } else {
            abort(404);
        }
    }
    public function printPR($code, Request $request){
        $printcontroller = new PrintController();
        return $printcontroller->printpurchaserequest($code);
    }
}
