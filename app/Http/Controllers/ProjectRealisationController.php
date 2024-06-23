<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Project_Detail_B_Realisation;
use App\Models\Project_Detail_Realisations;
use App\Models\ProjectRealisation;
use App\Models\Stock;
use App\Models\Stocks_Out;
use Carbon\Carbon;
use Database\Seeders\ProjectRelationSeed;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;

class ProjectRealisationController extends AdminController
{
    public function getViewProjectRealisation(Request $request){
           $supplyData = [
            'title' => 'Project Realisation',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    

            ];

        return response()->view("admin.project.projectrealisation",$supplyData);
    }
    public function getViewProjectRealisationManage(Request $request, $code=null){
        
        try {
            //code...
            $data =[];
            if ($code){ //If In Update Mode
    
                $realisasi = ProjectRealisation::join('customers', 'customers.code', '=', 'project_realisations.customer_code')
                ->join('projects', 'projects.code', '=', 'project_realisations.project_code')
                ->select('project_realisations.*', 'projects.name as project_name', 'customers.name as customer_name')
                ->where('project_realisations.code', $code)->first();

                if (!$realisasi){
                    abort(404);
                }
                $totalTermin = Project::where("code", $realisasi->project_code)->first()->total_termin;

                $jumlahRealisasi= ProjectRealisation::where('project_code',$realisasi->project_code )->count();

                if (intval($realisasi->termin) < intval($jumlahRealisasi)){
                    throw new Exception("Already Had New Realisation In The Same Project , Cannot Edit Previous Realisation");
                }

                $AllRealisation =  ProjectRealisation::where('project_code',$realisasi->project_code )->get();
                $dataCurrentMaterial = Project_Detail_Realisations::where('project_realisation_code', $code)->get();
                $dataCurrentUpah = Project_Detail_B_Realisation::where('project_realisation_code', $code)->get();

                $data['realisasi'] = $realisasi;
                $data['AllRealisation'] = json_encode($AllRealisation);
                $data['dataCurrentMaterial'] =json_encode($dataCurrentMaterial);
                $data['dataCurrentUpah'] = json_encode($dataCurrentUpah);
                $data['totalTermin'] = $totalTermin;
            }
            $supplyData = [
                'title' =>$request->route()->getName() == 'admin.addProjectrealisationview' ?  'Add New Realisation' : 'Edit Realisation',
                'users' => Auth::user(),
                'sessionRoute' =>  $request->route()->getName(),
                'data' => $data
                ];

    
            return response()->view("admin.project.projectrealisationmanage",$supplyData);
        } catch (\Throwable $th) {
            return $this->errorException($th,"admin.projectrealisationview", $code );
        }
            
    }

    public function getProjectRealisationtable(Request $request, DataTables $dataTables){
        if ($request->ajax()){

        
            $is_approve = intval($request->status) >=  0  ? $request->status : null ;
            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            
            
            $realisation =  ProjectRealisation::join("customers", 'project_realisations.customer_code', '=', 'customers.code')
                                                ->join('projects', 'project_realisations.project_code', '=', 'projects.code')
                            ->select('project_realisations.*', 'projects.name as project_name', 'projects.total_termin',  'customers.name as customer_name')
                            ->whereBetween('realisation_date', [$startDate,$endDate])
                            ->when($is_approve !== null , function($query) use($is_approve){
                                $query->where('is_approve', $is_approve);
                            });

            return $dataTables->of($realisation)
                ->editColumn('realisation_date', function($row) {
                    return Carbon::parse($row->realisation_date)->format('d/m/Y');
                })
                ->editColumn('project_amount', function($row) {
                    return "Rp " .number_format($row->project_amount,2, ',', '.');
                })
                ->editColumn('realisation_amount', function($row) {
                    return "Rp " .number_format($row->realisation_amount,2, ',', '.');
                })
                ->editColumn('percent_realisation', function($row) {
                    return floatval($row->percent_realisation);
                })
                ->filterColumn('customer_name', function($query, $keyword) {
                    $query->whereRaw("customers.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('project_name', function($query, $keyword) {
                    $query->whereRaw("projects.name LIKE ?", ["%{$keyword}%"]);
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
                ->addColumn('action', function ($row) {
                    $html = '';
                    switch ($row->is_approve) {
                        case 0: //Not Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->code.'" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->code.'" title="Delete"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->code.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning approvebtn" data-code="'.$row->code.'" title="Approve"><i class="fa fa-check"></i></button>
                            <a href="'.route('admin.printdetailprojectrealisation',['id' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-info printbtn" data-code="'.$row->code.'" title="Print Realisation"><i class="fa fa-print"></i></button></a>
                            </div>';
                            
                            # code...
                            break;
                            case 1: //Approved
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->code.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            <a href="'.route('admin.printdetailprojectrealisation',['id' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-info printbtn mr-2" data-code="'.$row->code.'" title="Print Realisation"><i class="fa fa-print"></i></button></a>
                            <a href="'.route('admin.printjurnalprojectrealisation',['id' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-warning printbtn" data-code="'.$row->code.'" title="Print Jurnal"><i class="fa fa-print"></i></button></a>
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
    public function getProjectRealisationSearchtable(Request $request,  DataTables $dataTables){
        if ($request->ajax()){


            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $pr = ProjectRealisation::where("is_approve", 1)
            ->join('projects', "projects.code", "=", "project_realisations.project_code")
            ->select("project_realisations.*", "projects.name as project_name")
            ->where("project_realisations.customer_code",  $request->customer_code)
            ->whereBetween('project_realisations.realisation_date', [$startDate,$endDate])
            ->whereNotIn('project_realisations.code', function($query){
                $query->select('project_realisation_code')
                ->from('invoices');
            });

            return $dataTables->of($pr)
            ->editColumn('realisation_date', function($row) {
                return $row->realisation_date ? Carbon::parse($row->realisation_date)->format('d/m/Y') : '';
            })
            ->filterColumn('project_name', function($query, $keyword) {
                $query->whereRaw("porjects.name LIKE ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($row) {

                return '
                <div class="d-flex justify-content-center">
                    <button class="btn btn-sm btn-success selectprojectrealisation" data-name="'.$row->project_name.'" data-amount="'.$row->realisation_amount.'" data-code="'.$row->code.'" title="Select"><i class="fa fa-check"></i> Select</button>
                </div>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);

        }
    }

    public function getProjectRealisationDataByProyek($code, Request $request){

        if($request->ajax()){

            $data = ProjectRealisation::where('project_code', $code)->get();

            return response()->json($data);

        } else {
            abort(404);
        }
    }

    public function getMaterialProyek($code,Request $request){
        if ($request->ajax()){
            $hasil =  DB::select("
            
               
                SELECT
                pd.project_code,
                pd.item_code,
                i.name as item_name,
                pd.unit_code,
                pd.qty as balance_awal,
                IFNULL(qry.total_qty,0) as total_used,
                (pd.qty - IFNULL(qry.total_qty,0)) as last_balance
                FROM
                project_details pd

                LEFT JOIN
                (
                SELECT
                pr.project_code,
				pdr.item_code,
                sum(IFNULL(pdr.qty_used,0)) as total_qty

                FROM
                project_realisations pr LEFT JOIN project_detail_realisations pdr 
                ON pr.`code` = pdr.project_realisation_code

                GROUP BY pr.project_code, pdr.item_code) qry

                ON pd.project_code = qry.project_code AND pd.item_code = qry.item_code

                INNER JOIN items i
                ON i.`code` = pd.item_code

                GROUP BY pd.project_code,pd.item_code,i.name ,pd.qty ,pd.unit_code, qry.total_qty
                Having pd.project_code = ?
            
            ", [$code]);


            return response()->json($hasil);
        } else {
            abort(404);
        }
    }

    public function getUpahProyek($code,Request $request){
        if ($request->ajax()){
            $hasil =  DB::select("
                
                     
                SELECT
                pdb.project_code,
                pdb.upah_code,
                u.job as job_name,
                pdb.unit,
                pdb.price,
                pdb.qty as balance_awal,
                IFNULL(qry.total_qty,0) as total_used,
                (pdb.qty - IFNULL(qry.total_qty,0)) as last_balance
                FROM
                project_detail_b pdb

                LEFT JOIN
                (
                SELECT
                pr.project_code,
								pdbr.upah_code,
                sum(IFNULL(pdbr.qty_used,0)) as total_qty

                FROM
                project_realisations pr LEFT JOIN project_detail_b_realisations pdbr 
                ON pr.`code` = pdbr.project_realisation_code 

                GROUP BY pr.project_code, pdbr.upah_code) qry

                ON pdb.project_code = qry.project_code AND pdb.upah_code = qry.upah_code 

                INNER JOIN upah u
                ON u.`code` = pdb.upah_code

                GROUP BY pdb.project_code,pdb.upah_code,u.job,pdb.unit,pdb.price, pdb.qty, qry.total_qty
                Having pdb.project_code = ?
            
            ", [$code]);


            return response()->json($hasil);
        } else {
            abort(404);
        }
    }

    public function addRealisation(Request $request){
        if ($request->ajax()){

            try {
                //code...
                DB::beginTransaction();
                $realisation = ProjectRealisation::orderBy("code", "desc")->lockforUpdate()->first();
                $data = $request->all();
                $realisation_no = $this->automaticCode('PRJR' ,$realisation, true,  'code');

                $detailA = json_decode($data['detailA']);
                $detailB = json_decode($data['detailB']);

                $data['realisation_date'] = Carbon::createFromFormat('d/m/Y',$data['realisation_date'])->format('Y-m-d');

                $PR = new ProjectRealisation();
                $PR->code = $realisation_no;
                $PR->project_code = $data['project_code'];
                $PR->customer_code = $data['customer_code'];
                $PR->realisation_date = $data['realisation_date'];
                $PR->project_amount = $data['project_amount'];
                $PR->percent_realisation = $data['percent_realisation'];
                $PR->realisation_amount = $data['realisation_amount'];
                $PR->termin = intval($data['termin']);
                $PR->is_approve = 0;
                $PR->description = $data['description'];
                $PR->created_by = Auth::user()->name;
                $PR->save();

                $Project = Project::where('code', $PR->project_code )->first();
                $Project->realisation_amount += $PR->realisation_amount;

                $defaultTermin = $Project->total_termin;

                // Mnemabhakan finisdate pada project saat termin terkahir
                if (intval($data['termin']) ==  $defaultTermin ){
                        $Project->end_date =  $PR->realisation_date;
                } 

                $Project->update();
        
                foreach ($detailA as $key => $value) {

                    $PDR = new Project_Detail_Realisations();
                    $PDR->project_realisation_code =$PR->code;
                    $PDR->item_code =$value->item_code;
                    $PDR->unit_code =$value->unit;

                    // Check Qty Sisa Di Akhir Termin
                    
                    if (intval($data['termin']) ==  intval($defaultTermin) && $value->sisa_qty > 0 ){
                        
                        $PDR->qty_additional =$value->sisa_qty;
                        $stocksOut = Stocks_Out::where('ref_no',$PR->project_code)->where("item_code", $PDR->item_code )->first();
                        
                        $stockController = new StockController();

                        $stockController->stockin($PDR->project_realisation_code,$PDR->item_code,$PDR->unit_code,$PR->realisation_date,$value->sisa_qty,floatval($stocksOut->cogs));

                    } else {
                        $PDR->qty_additional = floatval(0);
                    }
                    // END Check Qty Sisa Di Akhir Termin
                    $PDR->qty_used =  $value->current_qty;
                    $PDR->created_by = Auth::user()->username;
                    $PDR->save();

                }


                foreach ($detailB as $key => $value) {

                    $PDRB = new Project_Detail_B_Realisation();
                    $PDRB->project_realisation_code =$PR->code;
                    $PDRB->upah_code =$value->upah_code;
                    $PDRB->unit =$value->unit;

                    // Check Qty Sisa Di Akhir Termin

                    $defaultTermin = Project::where('code',$PR->project_code)->first()->total_termin;
                    
                    if (intval($data['termin']) ==  $defaultTermin && $value->balance_qty > 0 ){
                        
                        $PDRB->qty_additional =$value->balance_qty;

                    } else {
                        $PDR->qty_additional = floatval(0);
                    }
                    // END Check Qty Sisa Di Akhir Termin
                    $PDRB->qty_used =  floatval($value->current_qty);
                    $PDRB->price =  floatval($value->price);
                    $PDRB->total =  floatval($value->current_nominal);
                    $PDRB->created_by = Auth::user()->username;
                    $PDRB->save();

                }

                DB::commit();
                Session::flash('success',  "New Realisation : $PR->code Succesfully Created");
                return json_encode(true);
                
            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            
            }
        } else {
            abort(404);
        }
    }

    
    public function deleterealisation($id){
        try {
            DB::beginTransaction();
            $realisasi = ProjectRealisation::where('code', $id)->first();

            if (!$realisasi){
                abort(404);
            }

            $jumlahRealisasi= ProjectRealisation::where('project_code',$realisasi->project_code )->count();

            if (intval($realisasi->termin) < intval($jumlahRealisasi)){
                throw new Exception("Already Had New Realisation In The Same Project , Cannot Delete Previous Realisation");
            }

            $project = Project::where('code', $realisasi->project_code)->first();
            $project->realisation_amount -= $realisasi->realisation_amount;
            $project->end_date = null;
            $project->update();
            $realisasi->delete();
            Stocks_Out::where("ref_no",$id )->delete();

            DB::commit();
            
            return response()->redirectToRoute("admin.projectrealisationview")->with("success", "Data Realisation $id Successfully Deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            // Session::flash('error', $th->getMessage());
            return $this->errorException($th,"admin.projectrealisationview", $id );
        }
    }

    public function editrealisation($id , Request $request){
        if ($request->ajax()){

            try {
                //code...
                DB::beginTransaction();
                $data = $request->all();
                $detailA = json_decode($data['detailA']);
                $detailB = json_decode($data['detailB']);

                $data['realisation_date'] = Carbon::createFromFormat('d/m/Y',$data['realisation_date'])->format('Y-m-d');

                $PR = ProjectRealisation::where('code' ,$id)->first();
                // $PR->code = $realisation_no;
                $PR->project_code = $data['project_code'];
                $PR->customer_code = $data['customer_code'];
                $PR->realisation_date = $data['realisation_date'];
                $PR->project_amount = $data['project_amount'];


                
                $Project = Project::where('code', $PR->project_code )->first();
                $Project->realisation_amount -= $PR->realisation_amount;

                $PR->percent_realisation = $data['percent_realisation'];
                $PR->realisation_amount = $data['realisation_amount'];
                $PR->termin = intval($data['termin']);
                $PR->is_approve = 0;
                $PR->description = $data['description'];
                $PR->created_by = Auth::user()->name;
                $PR->updated_by = Auth::user()->name;
                $PR->update();

                $Project->realisation_amount = $Project->realisation_amount +  $PR->realisation_amount;

                $defaultTermin = $Project->total_termin;

                // Mnemabhakan finisdate pada project saat termin terkahir
                if (intval($data['termin']) ==  $defaultTermin ){
                        $Project->end_date =  $PR->realisation_date;
                } 

                $Project->update();
        

                Project_Detail_Realisations::where("project_realisation_code", $id )->delete();
                foreach ($detailA as $key => $value) {

                    $PDR = new Project_Detail_Realisations();
                    $PDR->project_realisation_code =$PR->code;
                    $PDR->item_code =$value->item_code;
                    $PDR->unit_code =$value->unit;

                    // Check Qty Sisa Di Akhir Termin
                    
                    if (intval($data['termin']) ==  $defaultTermin && $value->sisa_qty > 0 ){
                        Stock::where('ref_no',$PDR->project_realisation_code)->delete();
                        
                        $PDR->qty_additional =$value->sisa_qty;
                        $stocksOut = Stocks_Out::where('ref_no',$PR->project_code)->where("item_code", $PDR->item_code )->first();
                        
                        $stockController = new StockController();

                        $stockController->stockin($PDR->project_realisation_code,$PDR->item_code,$PDR->unit_code,$PR->realisation_date,$value->sisa_qty,floatval($stocksOut->cogs));

                    } else {
                        $PDR->qty_additional = floatval(0);
                    }
                    // END Check Qty Sisa Di Akhir Termin
                    $PDR->qty_used =  $value->current_qty;
                    $PDR->created_by = Auth::user()->username;
                    $PDR->save();

                }

                Project_Detail_B_Realisation::where("project_realisation_code", $id )->delete();
                foreach ($detailB as $key => $value) {

                    $PDRB = new Project_Detail_B_Realisation();
                    $PDRB->project_realisation_code =$PR->code;
                    $PDRB->upah_code =$value->upah_code;
                    $PDRB->unit =$value->unit;

                    // Check Qty Sisa Di Akhir Termin

                    $defaultTermin = Project::where('code',$PR->project_code)->first()->total_termin;
                    
                    if (intval($data['termin']) ==  $defaultTermin && $value->balance_qty > 0 ){
                        
                        $PDRB->qty_additional =$value->balance_qty;

                    } else {
                        $PDR->qty_additional = floatval(0);
                    }
                    // END Check Qty Sisa Di Akhir Termin
                    $PDRB->qty_used =  floatval($value->current_qty);
                    $PDRB->price =  floatval($value->price);
                    $PDRB->total =  floatval($value->current_nominal);
                    $PDRB->created_by = Auth::user()->username;
                    $PDRB->save();

                }

                DB::commit();
                Session::flash('success',  "Realisation : $PR->code Succesfully Updated");
                return json_encode(true);
                
            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            
            }
        } else {
            abort(404);
        }
    }

    public function approverealisation($id, Request $request){
        try {

            DB::beginTransaction();

            ProjectRealisation::where("code", $id)->update(
                
                [
                    
                    'is_approve' => 1,
                    'approved_by' => Auth::user()->name
                ]
            );

            $journal = new AccountingController();
            $journal->journalRealisasi($id);
        
            DB::commit();
            return response()->redirectToRoute("admin.projectrealisationview")->with("success", "Data Realisation $id Successfully Approved");
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorException($th,"admin.projectrealisationview", $id );
        }
    }

    public function printjurnalprojectrealisation($id){
    
        $printcontroller = new PrintController();
        return $printcontroller->printjurnalrealisasi($id);
    }

    public function getDetailRealisation($id, Request $request){

        if ($request->ajax()){
            try {
                //code...

                $dataBahanBaku = Project_Detail_Realisations::join('items', 'items.code', "=", "project_detail_realisations.item_code")
                                ->select('project_detail_realisations.*', 'items.name as item_name')
                                ->where('project_detail_realisations.project_realisation_code', $id)->get();
                $dataUpah = Project_Detail_B_Realisation::join('upah', 'upah.code', '=', 'project_detail_b_realisations.upah_code')
                                ->select('project_detail_b_realisations.*', 'upah.job as upah_name')
                                ->where('project_detail_b_realisations.project_realisation_code', $id)->get();
                $data = [
                    'dataBahanBaku' => $dataBahanBaku,
                    'dataUpah' => $dataUpah
                ];
            
                
                return json_encode($data);
                
            } catch (\Throwable $th) {
                throw new \Exception($th->getMessage());
            }
        } else {
            abort(404);
        }
    }

    public function printdetailprojectrealisation($id){
        $printcontroller = new PrintController();
        return $printcontroller->printprojectrealisationdetail($id);

    }

}
