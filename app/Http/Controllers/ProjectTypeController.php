<?php

namespace App\Http\Controllers;

use App\Models\Type_Project;
use App\Models\TypeProjectDetail;
use App\Models\TypeProjectDetailB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ProjectTypeController extends Controller
{
    public function getViewTypeProject(Request $request ){


        $supplyData = [
            'title' => 'Project Type',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
    

            ];

        return response()->view("admin.project.typeproject",$supplyData);
    }

    public function getViewManageTypeProject(Request $request,$code = null){

        $data = [];
        if ($code){

            $data['bahanBaku'] = TypeProjectDetail::join('items', 'items.code', "=", 'type_projects_details.item_code')
                                ->select("type_projects_details.*", 'items.name as item_name')
                                ->where('type_projects_details.type_project_code', $code)->get();
            $data['upah'] = TypeProjectDetailB::join('upah', 'upah.code', '=', 'type_projects_details_b.upah_code')
                            ->select('type_projects_details_b.*', 'upah.job')
                            ->where('type_projects_details_b.type_project_code' , $code)->get();
            $data['projecttype'] = Type_Project::where('type_projects.code', '=', $code)->first();

            if (!$data['projecttype']){
                abort(404);
            }

        }

        $supplyData = [
            'title' =>$request->route()->getName() == 'admin.addprojecttypeview' ?  'Add New Type Project' : 'Edit Type Project',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),
            'data' => $data
            ];
            // return $supplyData;

        return response()->view("admin.project.projectypemanage",$supplyData);

    }
    public function getDataTypeProject(Request $request, DataTables $dataTables){
        if ($request->ajax()){

            $projectType = Type_Project::query();
    
            
            return $dataTables->of($projectType)
            
            ->addColumn('action', function ($row) {
                
                return '
                <div class="d-flex justify-content-center">
                <button class="btn btn-sm btn-primary editbtn" data-code="'.$row->code.'" title="Edit"><i class="fa fa-edit"></i></button>
                <button class="btn btn-sm btn-info viewbtn" data-code="'.$row->code.'" title="View Detail"><i class="fa fa-eye"></i></button>
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

    public function updateProjectType(Request $request, $code){
        try {
            DB::beginTransaction();
            $name = $request->post("typename");
            $description = $request->post("description");
            $dataA = json_decode($request->post('detaila'));
            $dataB = json_decode($request->post('detailb'));
            
            $typeproject = Type_Project::where("code",$code )->first();

            $typeproject->name = $name;
            $typeproject->description = $description;
            $typeproject->updated_by = Auth::user()->username;
            $typeproject->update();

            TypeProjectDetail::where('type_project_code', $code)->delete();
            TypeProjectDetailB::where('type_project_code', $code)->delete();
            foreach($dataA as $item){

                $typeprojectdetail = new TypeProjectDetail();
                $typeprojectdetail->type_project_code = $code;
                $typeprojectdetail->item_code = $item->item_code;
                $typeprojectdetail->unit_code = $item->unit_code;
                $typeprojectdetail->save();
            }

            foreach($dataB as $item){

                $typeprojectdetailB = new TypeProjectDetailB();
                $typeprojectdetailB->type_project_code = $code;
                $typeprojectdetailB->upah_code = $item->upah_code;
                $typeprojectdetailB->unit = $item->unit;
                $typeprojectdetailB->price = floatval($item->price);
                $typeprojectdetailB->save();
            }
            // Session::flash('error', `Data Berhasil Disimpan`);

            DB::commit();
            Session::flash('success',  "Edit Type Project : $code Succesfully Updated");
            return json_encode(true);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }


    }

    public function deleteProjectType($code){
        try {
            Type_Project::where("code",$code )->delete();
            
            return response()->redirectToRoute("admin.projecttype")->with("success", "Data $code Successfully Deleted");
        } catch (\Throwable $th) {
            return $this->errorException($th,"admin.projecttype", $code );
            // Session::flash('error', $th->getMessage());
        }


    }

    public function addProjectType(Request $request){
        try {

            DB::beginTransaction();

            $supplyModel = Type_Project::orderBy("code", "desc")->lockForUpdate()->first();
            $code = $this->automaticCode("TYPE" ,$supplyModel, false,"code");
            $name = $request->post("typename");
            $description = $request->post("description");
            $dataA = json_decode($request->post('detaila'));
            $dataB = json_decode($request->post('detailb'));


            $typeProject = new Type_Project();
            $typeProject->code = $code;
            $typeProject->name =  $name;
            $typeProject->description = $description;
            $typeProject->created_by = Auth::user()->username;
            $typeProject->save();

            foreach($dataA as $item){

                $typeprojectdetail = new TypeProjectDetail();
                $typeprojectdetail->type_project_code = $code;
                $typeprojectdetail->item_code = $item->item_code;
                $typeprojectdetail->unit_code = $item->unit_code;
                $typeprojectdetail->save();
            }

            foreach($dataB as $item){

                $typeprojectdetailB = new TypeProjectDetailB();
                $typeprojectdetailB->type_project_code = $code;
                $typeprojectdetailB->upah_code = $item->upah_code;
                $typeprojectdetailB->unit = $item->unit;
                $typeprojectdetailB->price = floatval($item->price);
                $typeprojectdetailB->save();
            }

            DB::commit();

            Session::flash('success',  "New Type Project : $code Succesfully Created");
            return json_encode(true);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }


    }

    public function getDataTypeProjectRaw($id,Request $request ){
        if ($request->ajax()){
            $data['bahanBaku'] = TypeProjectDetail::join('items', 'items.code', "=", 'type_projects_details.item_code')
            ->select("type_projects_details.*", 'items.name as item_name')
            ->where('type_projects_details.type_project_code', $id)->get();
            $data['upah'] = TypeProjectDetailB::join('upah', 'upah.code', '=', 'type_projects_details_b.upah_code')
                    ->select('type_projects_details_b.*', 'upah.job')
                    ->where('type_projects_details_b.type_project_code' , $id)->get();
            $data['projecttype'] = Type_Project::where('type_projects.code', '=', $id)->first();
            

            return json_encode($data);

        } else {
            abort(404);
        }

    }

    public function getdataforproyek($id, Request $request){

        if($request->ajax()){
            $data['bahanBaku'] = TypeProjectDetail::join('items', 'items.code', "=", 'type_projects_details.item_code')
            ->leftjoin('stocks', "items.code", "=", "stocks.item_code")
            ->select("type_projects_details.item_code", "type_projects_details.unit_code", 'items.name as item_name', DB::raw('IFNULL(SUM(stocks.actual_stock - stocks.used_stock), 0) As stocks'))
            ->groupBy('type_projects_details.item_code','items.name' ,  "type_projects_details.unit_code")
            ->where('type_projects_details.type_project_code', $id)->get();

            $data['upah'] = TypeProjectDetailB::join('upah', 'upah.code', '=', 'type_projects_details_b.upah_code')
                    ->select('type_projects_details_b.*', 'upah.job')
                    ->where('type_projects_details_b.type_project_code' , $id)->get();
            return json_encode($data);
        } else{
            abort(404);
        }
    }

}
