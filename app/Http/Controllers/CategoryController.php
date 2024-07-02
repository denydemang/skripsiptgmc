<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CategoryController extends AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $supplyData = [
            'title' => 'Categorys',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),


        ];

        return response()->view("admin.master.category", $supplyData);
    }

    public function getDatacategorys(Request $request, DataTables $dataTables)
    {
        if ($request->ajax()) {

            $categorys = Category::query();


            // $users = User::query();

            return $dataTables->of($categorys)
                ->addColumn('action', function ($row) {

                    return '
                <div class="d-flex justify-content-center">
                <button class="btn btn-sm btn-primary editbtn" data-code="' . $row->code . '" title="Edit"><i class="fa fa-edit"></i></button>
                <button class="btn btn-sm btn-danger deletebtn" data-code="' . $row->code . '" title="Delete"><i class="fa fa-trash"></i></button>
                </div>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exist_category = Category::find($request->code);
        if (isset($exist_category)) {
            return response()->redirectToRoute("r_category.index")->with("error", "Code <b>" . $request->code . "</b> Sudah tersedia");
        } else {
            try {

                $supplyModel = Category::orderBy("code", "desc")->lockForUpdate()->first();
                $code_auto = $this->automaticCode("CTG", $supplyModel, false, "code");
                $code = $request->post("code");
                $name = $request->post("name");
                $coa_code = $request->post("coa_code");


                $typeProject = new Category();
                $typeProject->code = $code!='' ? $code : $code_auto;
                $typeProject->name =  $name;
                $typeProject->coa_code =  $coa_code;
                $typeProject->created_by = Auth::user()->username;
                $typeProject->save();

                // Session::flash('error', `Data Berhasil Disimpan`);

                return response()->redirectToRoute("r_category.index")->with("success", "Data ".$typeProject->code." Succesfully Created");
            } catch (\Throwable $th) {
                // Session::flash('error', $th->getMessage());
                return response()->redirectToRoute("r_category.index")->with("error", $th->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        if ($request->ajax()) {
            $dataProjectType = Category::query()->where("code", $id)->first();

            return json_encode($dataProjectType);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $name = $request->post("name");
            $coa_code = $request->post("coa_code");
            $getRole = Category::find($id);
            $getRole->name = $name;
            $getRole->coa_code = $coa_code;
            $getRole->updated_by = Auth::user()->username;
            $getRole->update();

            return response()->redirectToRoute("r_category.index")->with("success", "Data " . $name . " Successfully Updated");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_category.index")->with("error", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Category::where("code", $id)->delete();

            // return response()->redirectToRoute("r_category.index")->with("success", "Data ".$id." Successfully Deleted");
            return response()->json(['msg' => 'Data '.$id.' Successfully Deleted', 'status' => 'success', 'code' => 200]);

        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return $this->errorException($th,"r_category.index", $id );
        }
    }
}
