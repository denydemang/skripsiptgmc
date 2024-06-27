<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Journal_Detail;
use App\Models\Project;
use App\Models\Project_Detail;
use App\Models\Project_Detail_B_Realisation;
use App\Models\Project_Detail_Realisation;
use App\Models\Project_Detail_Realisations;
use App\Models\ProjectDetailB;
use App\Models\Stock;
use App\Models\Stocks_Out;
use App\Models\Type_Project;
use App\Models\Upah;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ProjectController extends AdminController
{

    public function getViewProject(Request $request){

        $supplyData = [
            'title' => 'Project',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    

            ];

        return response()->view("admin.project.project",$supplyData);
    }

    public function getViewProjectManage( Request $request, $code=null){

        
        if ($code){ //If In Update Mode

            $project = Project::join('type_projects', 'projects.project_type_code', '=', 'type_projects.code')
            ->join('customers','projects.customer_code', '=', 'customers.code' )
            ->select('projects.*', 'type_projects.name as type_project_name', 'type_projects.description as type_project_description', 
            'customers.name as customer_name', 'customers.address as customer_address')
            ->where('projects.code', $code)->first();

            if (!$project){
                abort(404);
            }
            $project_detail = new ProjectDetailController();
            $project_detail = $project_detail->getDetail($code);
            $dataBahanBaku = $project_detail->get();            

            $project_detail_b = new ProjectDetailBController();
            $project_detail_b = $project_detail_b->getDetailB($code);
            $dataUpah = $project_detail_b->get();
            $data = [
                'dataProject' => $project,
                'databahanBaku' =>json_encode($dataBahanBaku),
                'dataUpah' => json_encode($dataUpah)
            ];
        }
        $supplyData = [
            'title' =>$request->route()->getName() == 'admin.addProjectView' ?  'Add New Project' : 'Edit Project',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            'data' => $data
            ];

        return response()->view("admin.project.projectmanage",$supplyData);
    }

    public function getDataSearch(Request $request, DataTables $dataTables){

        $customerCode = $request->customer_code;

        $project = Project::select('projects.code' , 'projects.name', 'projects.budget','projects.realisation_amount', 'projects.total_termin', DB::raw('(Select count(*) from project_realisations where project_code = projects.code) as last_termin'))
                ->where('projects.total_termin', '<>', DB::raw('(Select count(*) from project_realisations where project_code =projects.code)'))
                ->where('projects.project_status', '>', 0)
                ->whereColumn('projects.budget', '>', 'projects.realisation_amount')
                ->where('projects.customer_code', '=', $customerCode);

        return $dataTables->of($project)
        ->editColumn('budget', function($row) {
            return "Rp " .number_format($row->budget,2, ',', '.');
        })
        ->editColumn('realisation_amount', function($row) {
            return "Rp " .number_format($row->realisation_amount,2, ',', '.');
        })
        ->editColumn('total_termin', function($row) {
            return intval($row->total_termin);
        })
        ->editColumn('last_termin', function($row) {
            return intval($row->last_termin);
        })
        ->addColumn('action', function ($row) {
            return '
            <div class="d-flex justify-content-center">
                <button class="btn btn-sm btn-success btnselectproject" data-name="'.$row->name.'" data-budget="'.$row->budget.'" data-totaltermin="'.$row->total_termin.'" data-lasttermin="'.$row->last_termin.'" data-code="'.$row->code.'" title="Select"><i class="fa fa-check"></i> Select</button>
            </div>';

        })

        ->rawColumns(['action','is_approve'])
        ->addIndexColumn()
        ->make(true);


    }

    public function projectrecapview(Request $request){
        $supplyData = [
            'title' =>"Project Recapitulation",
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()
            ];

        return response()->view("admin.project.projectrecap",$supplyData);
    }

    public function projectrealisationview(Request $request){
        $supplyData = [
            'title' =>"Project Realisation",
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()
            ];

        return response()->view("admin.project.projectrealisation",$supplyData);
    }

    public function projectrealisationfinishview($code, Request $request){



        try {
            $project = Project::join('type_projects', 'projects.project_type_code', '=', 'type_projects.code')
            ->join('customers','projects.customer_code', '=', 'customers.code' )
            ->select('projects.*', 'type_projects.name as type_project_name', 'type_projects.description as type_project_description', 
            'customers.name as customer_name', 'customers.address as customer_address')
            ->where("projects.code", $code)->first();

            if (!$project){
                abort(404);
            }

            if($project->project_status !== 1 ){
                abort(404);
            }
            $project_detail = new ProjectDetailController();
            $project_detail = $project_detail->getDetail($code);
            $dataBahanBaku = $project_detail->get();
            

            $project_detail_b = new ProjectDetailBController();
            $project_detail_b = $project_detail_b->getDetailB($code);
            $dataUpah = $project_detail_b->get();



            $supplyData = [
                'title' =>"Project Realisation Finish",
                'users' => Auth::user(),
                'sessionRoute' =>  $request->route()->getName(),
                'project'=> $project,
                "dataUpah" => json_encode($dataUpah),
                "dataBahanBaku" => json_encode($dataBahanBaku)
                ];
    
            return response()->view("admin.project.projectrealisationfinish",$supplyData);
        } catch (\Throwable $th) {
            abort(500);
        }
    }



 
    public function getDataProject(Request $request, DataTables $dataTables){

    
        if ($request->ajax()){


            $status = intval($request->status) >=  0  ? $request->status : null ;
            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $startProject = $request->startProject ? Carbon::createFromFormat('d/m/Y', $request->startProject)->format('Y-m-d') :null;
            $startProject2 = $request->startProject2 ? Carbon::createFromFormat('d/m/Y',  $request->startProject2)->format('Y-m-d')  : null;
            $EndProject = $request->EndProject ? Carbon::createFromFormat('d/m/Y',   $request->EndProject)->format('Y-m-d') : null;
            $EndProject2 = $request->EndProject2 ? Carbon::createFromFormat('d/m/Y',   $request->EndProject2)->format('Y-m-d')  : null ;


            $project = Project::join('type_projects', 'projects.project_type_code', '=', 'type_projects.code')
            ->join('customers','projects.customer_code', '=', 'customers.code' )
            ->select('projects.*', 'type_projects.name as type_project_name', 'type_projects.description as type_project_description', 
            'customers.name as customer_name', 'customers.address as customer_address')
            ->when($status !== null, function($query) use($status){
                switch ($status) {
                    case 0:
                        $query->where('project_status', 0);
                        break;
                    case 1:
                        $query->where('project_status', 1);
                        $query->whereColumn('budget', '>', 'realisation_amount');
                        break;
                    case 2:
                        $query->whereColumn('budget', '<=', 'realisation_amount');
                        break;
                }
            })
            ->when($startProject !== null && $startProject2 !== null, function($query) use($startProject , $startProject2){

                $query->where(function($query) use($startProject , $startProject2){
                    $query->whereBetween('start_date',[$startProject, $startProject2] );
                });
            })
            ->when($EndProject !== null && $EndProject2 !== null, function($query) use($EndProject , $EndProject2){

                $query->where(function($query) use($EndProject , $EndProject2){
                    $query->whereBetween('end_date',[$EndProject, $EndProject2]);
                });
            })
            ->whereBetween('transaction_date' , [$startDate, $endDate]);
            ;
        
                return $dataTables->of($project)
                ->editColumn('budget', function($row) {
                    return "Rp " .number_format($row->budget,2, ',', '.');
                })
                ->editColumn('realisation_amount', function($row) {
                    return "Rp " .number_format($row->realisation_amount,2, ',','.');
                })
                ->editColumn('transaction_date', function($row) {
                    return Carbon::parse($row->transaction_date)->format('d/m/Y');
                })
                ->editColumn('start_date', function($row) {
                        return $row->start_date ? Carbon::parse($row->start_date)->format('d/m/Y') : '';
                })
                ->editColumn('end_date', function($row) {
                    return $row->end_date ? Carbon::parse($row->end_date)->format('d/m/Y') : '';
                })
                ->editColumn('project_status', function($row) {
                    $html ="";

                    if ($row->budget - $row->realisation_amount > 0 && intval($row->project_status) == 0 ){
                        $html= "<span class='badge badge-danger'>Not Started</span>";
                    } else if($row->budget - $row->realisation_amount > 0 && intval($row->project_status) == 1)  {
                        $html= "<span class='badge badge-primary'>On Progress</span>";
                    } else {
                        $html= "<span class='badge badge-success'>Done</span>";
                    }
                    return $html;
                })
                ->editColumn('project_document', function($row) {
                    if( $row->project_document){

                        return "<a href='" . route('admin.download', ['idfile' => $row->project_document]) . "'><i class='fas fa-file-download' style='font-size:20px'></i> Download</a>";
                    } else{
                        return 'No File uploaded';
                    }
                })
                ->filterColumn('type_project_name', function($query, $keyword) {
                    $query->whereRaw("type_projects.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('type_project_description', function($query, $keyword) {
                    $query->whereRaw("type_projects.description LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('customer_name', function($query, $keyword) {
                    $query->whereRaw("customers.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('customer_address', function($query, $keyword) {
                    $query->whereRaw("customers.address LIKE ?", ["%{$keyword}%"]);
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    switch ($row->project_status) {
                        case 0: //Not Started
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->code.'" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->code.'" title="Delete"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->code.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning startbtn" data-code="'.$row->code.'" title="Start Project"><i class="ni ni-button-play"></i></button>
                            <a href="'.route('admin.printproject',['code' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-info printproject mr-2" data-code="'.$row->code.'" title="Print Project"><i class="fa fa-print"></i></button></a>
                            </div>';
                            
                            # code...
                            break;
                            case 1: //Started
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->code.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            <a href="'.route('admin.printproject',['code' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-info printproject mr-2" data-code="'.$row->code.'" title="Print Project"><i class="fa fa-print"></i></button></a>
                            <a href="'.route('admin.printjournal',['code' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-warning printjournalbtn" title="Print Journal"><i class="fa fa-print"></i></button></a>
                            </div>';
                            
                            # code...
                            break;
                            case 2: //Done
                                $html = '
                            <div class="d-flex justify-content-center">
                            <a href="'.route('admin.printproject',['code' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-info printproject mr-2" data-code="'.$row->code.'" title="Print Project"><i class="fa fa-print"></i></button></a>
                            <a href="'.route('admin.printjournal',['code' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-warning printjournalbtn" title="Print Journal"><i class="fa fa-print"></i></button></a>
                            </div>';
    
                            # code...
                            break;
                        
                        default:
                            # code...
                            break;
                    }
    
                    return $html;
                })
                ->rawColumns(['action','project_status','project_document' ])
                ->addIndexColumn()
                ->make(true);
                
     
        } else {
            abort(404);
        }
        
    }

    public function getDataProjectRealisation(Request $request, DataTables $dataTables){

    
        if ($request->ajax()){


            $status = intval($request->status) >=  0  ? intval($request->status) : null ;
            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $startProject = $request->startProject ? Carbon::createFromFormat('d/m/Y', $request->startProject)->format('Y-m-d') :null;
            $startProject2 = $request->startProject2 ? Carbon::createFromFormat('d/m/Y',  $request->startProject2)->format('Y-m-d')  : null;
            $EndProject = $request->EndProject ? Carbon::createFromFormat('d/m/Y',   $request->EndProject)->format('Y-m-d') : null;
            $EndProject2 = $request->EndProject2 ? Carbon::createFromFormat('d/m/Y',   $request->EndProject2)->format('Y-m-d')  : null ;

            $project = Project::join('type_projects', 'projects.project_type_code', '=', 'type_projects.code')
            ->join('customers','projects.customer_code', '=', 'customers.code' )
            ->select('projects.*', 'type_projects.name as type_project_name', 'type_projects.description as type_project_description', 
            'customers.name as customer_name', 'customers.address as customer_address')
            ->when($status !== null && $status === 3, function($query){
                $query->where(function($query){
                    $query->where('project_status', 1);
                    $query->orWhere('project_status', 2);
                });
            })
            ->when($status !== null && $status !== 3, function($query) use($status){
                $query->where('project_status', $status);
            })
            ->when($startProject !== null && $startProject2 !== null, function($query) use($startProject , $startProject2){

                $query->where(function($query) use($startProject , $startProject2){
                    $query->whereBetween('start_date',[$startProject, $startProject2] );
                });
            })
            ->when($EndProject !== null && $EndProject2 !== null, function($query) use($EndProject , $EndProject2){

                $query->where(function($query) use($EndProject , $EndProject2){
                    $query->whereBetween('end_date',[$EndProject, $EndProject2] );
                });
            })

            ->whereBetween('transaction_date' , [$startDate, $endDate]);
            ;
        
                return $dataTables->of($project)
                ->editColumn('budget', function($row) {
                    return "Rp " .number_format($row->budget,2, ',', '.');
                })
                ->editColumn('realisation_amount', function($row) {
                    return "Rp " .number_format($row->realisation_amount,2, '.');
                })
                ->editColumn('transaction_date', function($row) {
                    return Carbon::parse($row->transaction_date)->format('d/m/Y');
                })
                ->editColumn('start_date', function($row) {
                        return $row->start_date ? Carbon::parse($row->start_date)->format('d/m/Y') : '';
                })
                ->editColumn('end_date', function($row) {
                    return $row->end_date ? Carbon::parse($row->end_date)->format('d/m/Y') : '';
                })
                ->editColumn('project_status', function($row) {
                    $html ="";
                    switch ($row->project_status) {
                        case 0:
                            $html= "<span class='badge badge-danger'>Not Started</span>";
                            break;
                        case 1:
                            $html= "<span class='badge badge-primary'>On Progress</span>";
                            break;
                        case 2:
                            $html= "<span class='badge badge-success'>Done</span>";
                            break;
                        default:
                            break;
                    }
                    return $html;
                })
                ->editColumn('project_document', function($row) {
                    if( $row->project_document){

                        return "<a href='" . route('admin.download', ['idfile' => $row->project_document]) . "'><i class='fas fa-file-download' style='font-size:20px'></i> Download</a>";
                    } else{
                        return 'No File uploaded';
                    }
                })
                ->filterColumn('type_project_name', function($query, $keyword) {
                    $query->whereRaw("type_projects.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('type_project_description', function($query, $keyword) {
                    $query->whereRaw("type_projects.description LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('customer_name', function($query, $keyword) {
                    $query->whereRaw("customers.name LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('customer_address', function($query, $keyword) {
                    $query->whereRaw("customers.address LIKE ?", ["%{$keyword}%"]);
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    switch ($row->project_status) {
                        case 0: //Not Started
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->code.'" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->code.'" title="Delete"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->code.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning startbtn" data-code="'.$row->code.'" title="Start Project"><i class="ni ni-button-play"></i></button>
                            <a href="'.route('admin.printproject',['code' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-info printproject mr-2" data-code="'.$row->code.'" title="Print Project"><i class="fa fa-print"></i></button></a>
                            </div>';
                            
                            # code...
                            break;
                            case 1: //Started or onprogress
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->code.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            <a href="'.route('admin.projectrealisationfinishview',['id' => $row->code]).'"><button class="btn btn-sm btn-danger finishproject mr-2" data-code="'.$row->code.'" title="Finish Project"><i class="fas fa-stop-circle"></i></button></a>
                            <a href="'.route('admin.printproject',['code' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-info printproject mr-2" data-code="'.$row->code.'" title="Print Project"><i class="fa fa-print"></i></button></a> 
                            <a href="'.route('admin.printjournal',['code' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-warning printjournalbtn" title="Print Journal"><i class="fa fa-print"></i></button></a>
                            </div>';
                                
                            # code...
                            break;
                            case 2: //Done
                                $html = '
                            <div class="d-flex justify-content-center">
                            <a href="'.route('admin.printproject',['code' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-info printproject mr-2" data-code="'.$row->code.'" title="Print Project"><i class="fa fa-print"></i></button></a>
                            <a href="'.route('admin.printjournal',['code' => $row->code]).'" target="_blank"><button class="btn btn-sm btn-warning printjournalbtn" title="Print Journal"><i class="fa fa-print"></i></button></a>
                            </div>';
    
                            # code...
                            break;
                        
                        default:
                            # code...
                            break;
                    }
    
                    return $html;
                })
                ->rawColumns(['action','project_status','project_document' ])
                ->addIndexColumn()
                ->make(true);

            
                
     
        } else {
            abort(404);
        }
        
    }



    public function editProject($id, Request $request){
        if($request->ajax()){
            try {
                //code...
                DB::beginTransaction();
                $fileName = '';
                $data = $request->all();
    
                $dataprojectdetails = json_decode($data['project_details']);
                $dataprojectdetailb = json_decode($data['project_detail_b']);
                $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');

                $stock =  new StockController();
                $project = Project::where('code', $id)->first();

                foreach($dataprojectdetails as $i){
                    $stock->refreshstock($i->item_code,floatval($i->qty), $data['transaction_date'], $id);
                }

                if ($data['file']){

                    $fileController = new FileController();
                    $fileController->deleteFile($project->project_document);
                    $fileName = $fileController->uploadFile($data['file']);
                    $project->project_document = $fileName;
                }

                $project->name = $data['name'];
                $project->transaction_date = $data['transaction_date'];
                $project->project_type_code = $data['project_type_code'];
                $project->customer_code = $data['customer_code'];
                $project->location = $data['location'];
                $project->budget = $data['budget'];
                $project->project_status = 0;
                $project->description = $data['description'];
                $project->coa_expense = $data['coa_expense'];
                $project->coa_payable = $data['coa_payable'];
                $project->total_termin = intval($data['total_termin']);
                $project->pic = $data['pic'];
                $project->duration_days = $data['duration_days'];
                $project->updated_by = Auth::user()->username;
                $project->update();


                Project_Detail::where('project_code' , $id)->delete();
                foreach($dataprojectdetails as $i){
                    $project_details= new Project_Detail();
                    $project_details->project_code = $id;
                    $project_details->item_code = $i->item_code;
                    $project_details->unit_code = $i->unit_code;
                    $project_details->qty = $i->qty;
                    $project_details->updated_by = Auth::user()->username;
                    $project_details->created_by = Auth::user()->username;
                    $project_details->save();
                }

                ProjectDetailB::where('project_code', $id)->delete();
                foreach($dataprojectdetailb as $x){
                    $projectdetailb = new ProjectDetailB();
                    $projectdetailb->project_code = $id;
                    $projectdetailb->upah_code = $x->upah_code;
                    $projectdetailb->unit = $x->unit;
                    $projectdetailb->qty = $x->qty;
                    $projectdetailb->price = $x->price;
                    $projectdetailb->total = $x->total;
                    $projectdetailb->created_by =  Auth::user()->username;
                    $projectdetailb->updated_by =  Auth::user()->username;
                    $projectdetailb->save();
                }


                DB::commit();
                Session::flash('success',  "Project :  $id Succesfully Updated");
                return json_encode(true);
                
            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }
        }
    }

    

    public function addProject(Request $request ){

        if($request->ajax()){

            try {
                //code...
                DB::beginTransaction();
                $fileName = '';
                $project = Project::orderBy("code", "desc")->lockforUpdate()->first();
                $data = $request->all();
                $project_code = $this->automaticCode('PRJ' ,$project, true,  'code');
    
                $dataprojectdetails = json_decode($data['project_details']);
                $dataprojectdetailb = json_decode($data['project_detail_b']);
                $data['transaction_date'] = Carbon::createFromFormat('d/m/Y',$data['transaction_date'])->format('Y-m-d');
    
                $data['code'] =  $project_code;

                $stock =  new StockController();

                foreach($dataprojectdetails as $i){
                    $stock->stockout($i->item_code,$i->qty, $data['transaction_date'], $project_code);
                }

                if ($data['file']){

                    $fileController = new FileController();
                    $fileName = $fileController->uploadFile($data['file']);
                }

                $project = new Project();
                $project->code = $data['code'];
                $project->name = $data['name'];
                $project->transaction_date = $data['transaction_date'];
                $project->project_type_code = $data['project_type_code'];
                $project->customer_code = $data['customer_code'];
                $project->location = $data['location'];
                $project->budget = $data['budget'];
                $project->project_status = 0;
                $project->description = $data['description'];
                $project->coa_expense = $data['coa_expense'];
                $project->coa_payable = $data['coa_payable'];
                $project->pic = $data['pic'];
                $project->total_termin = intval($data['total_termin']);
                $project->realisation_amount = 0;
                $project->project_document = $fileName;
                $project->duration_days = $data['duration_days'];
                $project->created_by = Auth::user()->username;
                $project->save();


                foreach($dataprojectdetails as $i){
                    $project_details= new Project_Detail();
                    $project_details->project_code = $project_code;
                    $project_details->item_code = $i->item_code;
                    $project_details->unit_code = $i->unit_code;
                    $project_details->qty = $i->qty;
                    $project_details->created_by = Auth::user()->username;
                    $project_details->save();
                }

                foreach($dataprojectdetailb as $x){
                    $projectdetailb = new ProjectDetailB();
                    $projectdetailb->project_code = $project_code;
                    $projectdetailb->upah_code = $x->upah_code;
                    $projectdetailb->unit = $x->unit;
                    $projectdetailb->qty = $x->qty;
                    $projectdetailb->price = $x->price;
                    $projectdetailb->total = $x->total;
                    $projectdetailb->created_by =  Auth::user()->username;
                    $projectdetailb->save();
                }


                DB::commit();
                Session::flash('success',  "New Project :  $project_code Succesfully Created");
                return json_encode(true);
                
            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }



        } else {
            abort(404);
        }
    }
    


    public function deleteProject($code){
        try {
            DB::beginTransaction();
            $project = Project::where("code", $code)->first();
            $stockcontroller =new  StockController();
            $stockcontroller->revertstock($code);
            Project::where("code",$code )->delete();
            $filecontroller = new FileController();
            $filecontroller->deleteFile($project->project_document);
            DB::commit();

            return response()->redirectToRoute("admin.project")->with("success", "Data $code Successfully Deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            // Sess ion::flash('error', $th->getMessage());
            return $this->errorException($th,"admin.project", $code );
        }


    }


    public function getDataDetailProjectRaw($id,Request $request ){

        if ($request->ajax()){
            $project_detail = new ProjectDetailController();
            $project_detail = $project_detail->getDetail($id);
            $dataBahanBaku = $project_detail->get();
            

            $project_detail_b = new ProjectDetailBController();
            $project_detail_b = $project_detail_b->getDetailB($id);
            $dataUpah = $project_detail_b->get();
            $data = [
                'dataBahanBaku' => $dataBahanBaku,
                'dataUpah' => $dataUpah
            ];
        
            
            return json_encode($data);

        } else {
            abort(404);
        }

    }

    public function startProject($code, Request $request){

        if($request->ajax()){

            try {
                DB::beginTransaction();
    
            
                $project = Project::where("code", $code)->first();
                $accounting = new AccountingController();
                $accounting->journalstartproject($code, "JU", $project);

                // Update status project
                $project->project_status = 1;
                $project->start_date = Carbon::now();
                $project->update();
    
            
    
                DB::commit();
                Session::flash('success',  "Project : $project->code Succesfully Started");
                return json_encode(true);
    
    
            } catch (\Throwable $th) {
                DB::rollBack();
                throw new \Exception($th->getMessage());
            }

        } else {
            abort(404);
        }


    }

    public function finishproject($code, Request $request){

        $realisation_a =json_decode($request->post('materials'));
        $realisation_b = json_decode($request->post('upahs'));
        $finishDate = Carbon::createFromFormat('d/m/Y', $request->post('finish_date'))->format('Y-m-d');
    
        try {
            DB::beginTransaction();
            $project = Project::where("code", $code)->first();
            $project->project_status = 2;
            $project->end_date = $finishDate;
            $project->update();

            // MATERIAL
            // ==================================================
            $JumlahItemCodeSisa = 0;
            $JumlahItemCodeKurang = 0;
            foreach ($realisation_a as $a) {
                $PRA = new Project_Detail_Realisations();
                $PRA->project_code = $code;
                $PRA->item_code = $a->item_code;
                $PRA->unit_code = $a->unit_code;
                $PRA->qty_estimated = $a->qty_estimated;
                $PRA->qty_used = $a->qty_used;
                $PRA->created_by = Auth::user()->username;
                $PRA->updated_by = Auth::user()->username;
                $PRA->save();

                if ( $PRA->qty_estimated  != $PRA->qty_used ){
                    if ($PRA->qty_estimated > $PRA->qty_used ){ //SISA MATERIAL BErarti ada barang masuk persediaan
                        $JumlahItemCodeSisa++;
                        $SisaQTY = $PRA->qty_estimated - $PRA->qty_used;
                        $stocks_out = Stocks_Out::where('ref_no' ,  $PRA->project_code)->where('item_code',$PRA->item_code)->orderBy("item_date", "DESC")->orderBy("id", "desc")->get();
                        $stock = new StockController();
                        foreach ($stocks_out as $s) { //Untuk mendapatkan COGS Barang masuk dengan melihat history cogs barang keluar
                            if ($SisaQTY > 0){
                                if ($SisaQTY >= $s->qty){
                                    $stock->stockin( $PRA->project_code .'-Realisation',  $PRA->item_code,  $PRA->unit_code,$finishDate, $s->qty,$s->cogs);
    
                                    $SisaQTY-= $s->qty;

                                } else {
                                    $stock->stockin( $PRA->project_code.'-Realisation',  $PRA->item_code,  $PRA->unit_code,$finishDate, $SisaQTY,$s->cogs);
    
                                    $SisaQTY-= $SisaQTY;
                                }
                            }
                        }
                    }
                    else { // Ada Meterial Yang Kurang Maka ada persediaan yang keluar
                        $JumlahItemCodeKurang++;
                        $kurangQTY = $PRA->qty_used - $PRA->qty_estimated;
                        $stock = new StockController();
                        $stock->stockout($PRA->item_code,$kurangQTY, $finishDate, $PRA->project_code.'-Realisation');

                    }
                }

            }
            // ======================================================

            // UPAH
            // =======================================================
            $selisihQtyUpah = 0;
            foreach ($realisation_b as $b) {
                $PRB = new Project_Detail_B_Realisation();
                if (floatval($b->qty_estimated) != floatval($b->qty_used )){
                    $selisihQtyUpah++;
                }

                $PRB->project_code = $code;
                $PRB->upah_code = $b->upah_code;
                $PRB->unit= $b->unit;
                $PRB->qty_estimated = $b->qty_estimated;
                $PRB->qty_used = $b->qty_used;
                $PRB->price= $b->price;
                $PRB->total= $b->total;
                $PRB->created_by= Auth::user()->username;
                $PRB->updated_by= Auth::user()->username;
                $PRB->save();
            }
            // ======================================================= 

            DB::commit();

            DB::beginTransaction();
            $journal = new AccountingController();
            // INSERT JOURNAL Realisasi
            $journal->journalFinishProyek($code, "JU",$project, $finishDate);


            // INSERT JOURNAL PENYESUAIAN
            if ( $JumlahItemCodeSisa > 0 ||  $JumlahItemCodeKurang > 0 || $selisihQtyUpah > 0){
                $journal->journalPenyesuaianRealisationProyek($code, $finishDate);
        
            }
            DB::commit();

            Session::flash('success',  "Project : $code Succesfully Finished");
            return json_encode(true);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
        

        
        
    }
    
    public function printjournal($id){
        $printcontroller = new PrintController();
        return $printcontroller->printJournalProject($id);
    }

    public function printproject($id){
        $printcontroller = new PrintController();
        return $printcontroller->printProject($id);
    }

    public function printprojectrecap(Request $request){

        $statusCode = $request->get("statusCode");
        $firstDate = $request->get("firstDate");
        $lastDate = $request->get("lastDate");
        $customercode = $request->get("customercode");



        $printcontroller = new PrintController();
        return $printcontroller->printprojectrecap($statusCode, $firstDate, $lastDate,$customercode);
    }





}
