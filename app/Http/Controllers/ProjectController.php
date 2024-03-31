<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Project_Detail;
use App\Models\ProjectDetailB;
use App\Models\Type_Project;
use App\Models\Upah;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function getDataProject(Request $request, DataTables $dataTables){

    
        if ($request->ajax()){


            $status = intval($request->status) >=  0  ? $request->status : null ;
            $startDate = $request->startDate;
            $endDate = $request->endDate;
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
                    return Carbon::parse($row->start_Date)->format('d/m/Y');
                })
                ->editColumn('end_date', function($row) {
                    return Carbon::parse($row->end_date)->format('d/m/Y');
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
                            </div>';
    
                            # code...
                            break;
                        case 1: //Started
                            $html = '
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-success viewbtn" data-code="'.$row->code.'" title="View Detail"><i class="fa fa-eye"></i></button>
                            </div>';
    
                            # code...
                            break;
                        case 2: //Done
                            $html = '
                            <div class="d-flex justify-content-center">
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
                ->rawColumns(['action','project_status' ])
                ->addIndexColumn()
                ->make(true);
     
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
            Project::where("code",$code )->delete();

            return response()->redirectToRoute("admin.project")->with("success", "Data $code Successfully Deleted");
        } catch (\Throwable $th) {
            // Sess ion::flash('error', $th->getMessage());
            return response()->redirectToRoute("admin.project")->with("error", $th->getMessage());
        }


    }

    public function getDataTypeProjectRaw($id,Request $request ){
        if ($request->ajax()){
            $dataProjectType = Type_Project::query()->where("code", $id)->first();
            
            return json_encode($dataProjectType);

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

        }

    }


}
