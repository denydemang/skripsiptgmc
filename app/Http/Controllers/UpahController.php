<?php

namespace App\Http\Controllers;

use App\Models\Upah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UpahController extends AdminController
{
    public function getTableUpahSearch(Request $request, DataTables $dataTables)
    {

        if ($request->ajax()) {

            $upah = Upah::query();

            return $dataTables->of($upah)
                ->addColumn('action', function ($row) {
                    $data = '<div class="d-flex justify-content-center">
                    <button class="btn btn-sm btn-success selectupahbtn" data-code="' . $row->code . '" data-job="' . $row->job . '" data-description="' . $row->description . '" data-unit="' . $row->unit . '" data-price="' . $row->price . '" title="Select Upah"><i class="fa fa-check"></i> Select</button>
                    </div>';
                    return $data;
                })
                ->editColumn('price', function ($row) {
                    return "Rp " . number_format($row->price, 2, ",", ".");
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function index(Request $request, DataTables $dataTables)
    {
        if ($request->ajax()) {

            $upah = Upah::query();

            return $dataTables->of($upah)
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

        $supplyData = [
            'title' => 'Upah',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),


        ];

        return response()->view("admin.master.upah", $supplyData);
    }
    public function store(Request $request)
    {
        $exist_upah = Upah::find($request->code);
        if (isset($exist_upah)) {
            return response()->redirectToRoute("r_upah.index")->with("error", "Code <b>" . $request->code . "</b> Sudah tersedia");
        } else {
            try {
                $supplyModel = Upah::orderBy("code", "desc")->lockForUpdate()->first();
                $code_auto = $this->automaticCode("UPAH", $supplyModel, false, "code");
                $code = $request->post("code");
                $job = $request->post("job");
                $description = $request->post("description");
                $unit = $request->post("unit");
                $price = $request->post("price");
                $coa_code = $request->post("coa_code");


                $typeProject = new Upah();
                $typeProject->code = $code!='' ? $code : $code_auto;
                $typeProject->job =  $job;
                $typeProject->description =  $description;
                $typeProject->unit = $unit;
                $typeProject->price =  $price;
                $typeProject->coa_code =  $coa_code;
                $typeProject->created_by = Auth::user()->username;
                $typeProject->save();

                // Session::flash('error', `Data Berhasil Disimpan`);

                return response()->redirectToRoute("r_upah.index")->with("success", "Data ".$typeProject->code." Succesfully Created");
            } catch (\Throwable $th) {
                // Session::flash('error', $th->getMessage());
                return response()->redirectToRoute("r_upah.index")->with("error", $th->getMessage());
            }
        }
    }

    public function edit(string $id, Request $request)
    {
        if ($request->ajax()) {
            $dataProjectType = Upah::query()->where("code", $id)->first();

            return json_encode($dataProjectType);
        }
    }

    public function update(Request $request, string $id)
    {
        try {

            $job = $request->post("job");
            $description = $request->post("description");
            $unit = $request->post("unit");
            $price = $request->post("price");
            $coa_code = $request->post("coa_code");

            $getRole = Upah::find($id );
            $getRole->job = $job;
            $getRole->description = $description;
            $getRole->unit = $unit;
            $getRole->price = $price;
            $getRole->coa_code = $coa_code;
            $getRole->updated_by = Auth::user()->username;
            $getRole->update();

            return response()->redirectToRoute("r_upah.index")->with("success", "Data ".$id." Successfully Updated");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_upah.index")->with("error", $th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            Upah::where("code",$id )->delete();

            return response()->redirectToRoute("r_upah.index")->with("success", "Data ".$id." Successfully Deleted");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            // return response()->redirectToRoute("r_upah.index")->with("error", $th->getMessage());
            return $this->errorException($th,"r_upah.index", $id );
        }
    }
}
