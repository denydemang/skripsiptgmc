<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Journal_Detail;
use App\Models\Project;
use App\Models\Project_Detail;
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
    public function getViewTypeProject(Request $request){

        $supplyData = [
            'title' => 'Project Type',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    

            ];

        return response()->view("admin.project.typeproject",$supplyData);
    }

    public function getViewProject(Request $request){

        $supplyData = [
            'title' => 'Project',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    

            ];

        return response()->view("admin.project.project",$supplyData);
    }

    public function getViewProjectManage( Request $request, $code=null){

        $data = [];
        if ($code){ //If In Update Mode

            $project = Project::join('type_projects', 'projects.project_type_code', '=', 'type_projects.code')
            ->join('customers','projects.customer_code', '=', 'customers.code' )
            ->select('projects.*', 'type_projects.name as type_project_name', 'type_projects.description as type_project_description', 
            'customers.name as customer_name', 'customers.address as customer_address')
            ->where('projects.code', $code)->first();

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

    public function projectrecapview(Request $request){
        $supplyData = [
            'title' =>"Project Recapitulation",
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName()
            ];

        return response()->view("admin.project.projectrecap",$supplyData);
    }


    public function getDataTypeProject(Request $request, DataTables $dataTables){
        if ($request->ajax()){

            $projectType = Type_Project::query();
    
            
            return $dataTables->of($projectType)
            
            ->addColumn('action', function ($row) {
                
                return '
                <div class="d-flex justify-content-center">
                <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->code.'" title="Edit"><i class="fa fa-edit"></i></button>
                <button class="btn btn-sm btn-danger deletebtn" data-code="'.$row->code.'" title="Delete"><i class="fa fa-trash"></i></button>
                </div>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);

        }

    }
    public function getSearchtable(Request $request, DataTables $dataTables){

        $projectType = Type_Project::query();
        return $dataTables->of($projectType)
            
        ->addColumn('action', function ($row) {
            
            return '
            <div class="d-flex justify-content-center">
            <button class="btn btn-sm btn-success selectprojecttype" data-code="'.$row->code.'" data-name="'.$row->name.'" title="Select Type"><i class="fa fa-check"></i> Select</button>
            </div>';
        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);

    }
    public function getDataProject(Request $request, DataTables $dataTables){

    
        if ($request->ajax()){


            $status = intval($request->status) >=  0  ? $request->status : null ;
            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $startProject = $request->startProject ?  $request->startProject :null;
            $startProject2 = $request->startProject2 ?  $request->startProject2 : null;
            $EndProject = $request->EndProject ?  $request->EndProject : null;
            $EndProject2 = $request->EndProject2 ? $request->EndProject2 : null ;


            $project = Project::join('type_projects', 'projects.project_type_code', '=', 'type_projects.code')
            ->join('customers','projects.customer_code', '=', 'customers.code' )
            ->select('projects.*', 'type_projects.name as type_project_name', 'type_projects.description as type_project_description', 
            'customers.name as customer_name', 'customers.address as customer_address')
            ->when($status !== null, function($query) use($status){
                $query->where('project_status', intval($status));
            })
            ->when($startProject !== null && $startProject2 !== null, function($query) use($startProject , $startProject2){
                $query->whereBetween('start_date',[$startProject, $startProject2] );
            })
            ->when($EndProject !== null && $EndProject2 !== null, function($query) use($EndProject , $EndProject2){
                $query->whereBetween('end_date',[$EndProject, $EndProject2] );
            })
            ->whereBetween('transaction_date' , [$startDate, $endDate]);
            ;
        
                return $dataTables->of($project)
                ->editColumn('budget', function($row) {
                    return "Rp " .number_format($row->budget,2, '.');
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
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->code.'" title="View Detail"><i class="fa fa-eye"></i></button>
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

    public function updateProjectType(Request $request){
        try {
            $code =  $request->post("code");
            $name = $request->post("name");
            $description = $request->post("description");
            
            $typeproject = Type_Project::where("code",$code )->first();

            $typeproject->name = $name;
            $typeproject->description = $description;
            $typeproject->updated_by = Auth::user()->username;
            $typeproject->update();
            // Session::flash('error', `Data Berhasil Disimpan`);

            return response()->redirectToRoute("admin.projecttype")->with("success", "Data $code Successfully Updated");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("admin.projecttype")->with("error", $th->getMessage());
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

    
    public function addProjectType(Request $request){
        try {

            $supplyModel = Type_Project::orderBy("code", "desc")->lockForUpdate()->first();
            $code = $this->automaticCode("TYPE" ,$supplyModel, false,"code");
            $name = $request->post("name");
            $description = $request->post("description");


            $typeProject = new Type_Project();
            $typeProject->code = $code;
            $typeProject->name =  $name;
            $typeProject->description = $description;
            $typeProject->created_by = Auth::user()->username;
            $typeProject->save();
            
            // Session::flash('error', `Data Berhasil Disimpan`);

            return response()->redirectToRoute("admin.projecttype")->with("success", "Data $code Succesfully Created");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("admin.projecttype")->with("error", $th->getMessage());
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
    
    public function deleteProjectType($code){
        try {
            Type_Project::where("code",$code )->delete();
            
            return response()->redirectToRoute("admin.projecttype")->with("success", "Data $code Successfully Deleted");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("admin.projecttype")->with("error", $th->getMessage());
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
            return response()->redirectToRoute("admin.project")->with("error", $th->getMessage());
        }


    }

    public function getDataTypeProjectRaw($id,Request $request ){
        if ($request->ajax()){
            $dataProjectType = Type_Project::query()->where("code", $id)->first();
            
            return json_encode($dataProjectType);

        } else {
            abort(404);
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
