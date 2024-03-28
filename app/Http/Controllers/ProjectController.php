<?php

namespace App\Http\Controllers;

use App\Models\Type_Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ProjectController extends AdminController
{
    public function getViewTypeProject(Request $request, DataTables $dataTables){

        $supplyData = [
            'title' => 'Project Type',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    

            ];

        return response()->view("admin.project.typeproject",$supplyData);
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
    public function getDataTypeProjectRaw($id,Request $request ){
        if ($request->ajax()){
            $dataProjectType = Type_Project::query()->where("code", $id)->first();
            
            return json_encode($dataProjectType);

        }

    }


}
