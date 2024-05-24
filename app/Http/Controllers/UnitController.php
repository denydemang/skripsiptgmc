<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UnitController extends AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $supplyData = [
            'title' => 'Units',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),


        ];

        return response()->view("admin.master.unit", $supplyData);
    }

    public function getDataUnits(Request $request, DataTables $dataTables)
    {
        if ($request->ajax()) {

            $units = Unit::query();


            // $users = User::query();

            return $dataTables->of($units)
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
        $exist_unit = Unit::find($request->code);
        if (isset($exist_unit)) {
            return response()->redirectToRoute("r_unit.index")->with("error", "Code <b>" . $request->code . "</b> Sudah tersedia");
        } else {
            try {

                $supplyModel = Unit::orderBy("code", "desc")->lockForUpdate()->first();
                $code_auto = $this->automaticCode("UN_0", $supplyModel, false, "code");
                $code = $request->post("code");
                $name = $request->post("name");


                $typeProject = new Unit();
                $typeProject->code = $code!='' ? $code : $code_auto;
                $typeProject->name =  $name;
                $typeProject->created_by = Auth::user()->username;
                $typeProject->save();

                // Session::flash('error', `Data Berhasil Disimpan`);

                return response()->redirectToRoute("r_unit.index")->with("success", "Data ".$typeProject->code." Succesfully Created");
            } catch (\Throwable $th) {
                // Session::flash('error', $th->getMessage());
                return response()->redirectToRoute("r_unit.index")->with("error", $th->getMessage());
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
            $dataProjectType = Unit::query()->where("code", $id)->first();

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
            $getRole = Unit::find($id);
            $getRole->name = $name;
            $getRole->updated_by = Auth::user()->username;
            $getRole->update();

            return response()->redirectToRoute("r_unit.index")->with("success", "Data " . $name . " Successfully Updated");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_unit.index")->with("error", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            unit::where("code", $id)->delete();

            return response()->redirectToRoute("r_unit.index")->with("success", "Data Successfully Deleted");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            // return response()->redirectToRoute("r_unit.index")->with("error", $th->getMessage());
            return $this->errorException($th,"r_unit.index", $id );
        }
    }
}
