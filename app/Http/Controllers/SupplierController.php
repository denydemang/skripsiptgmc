<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SupplierController extends AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $supplyData = [
            'title' => 'Supplier',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),


        ];

        return response()->view("admin.master.supplier", $supplyData);
    }

    public function getDatasuppliers(Request $request, DataTables $dataTables) {
        if ($request->ajax()) {

            $suppliers = Supplier::query();


            // $users = User::query();

            return $dataTables->of($suppliers)
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
        try {

            $supplyModel = Supplier::orderBy("code", "desc")->lockForUpdate()->first();
            $code = $this->automaticCode("SUPP" ,$supplyModel, false,"code");
            $name = $request->post("name");
            $address = $request->post("address");
            $zip_code = $request->post("zip_code");
            $npwp = $request->post("npwp");
            $email = $request->post("email");
            $phone = $request->post("phone");
            $coa_code = $request->post("coa_code");


            $typeProject = new Supplier();
            $typeProject->code = $code;
            $typeProject->name =  $name;
            $typeProject->address =  $address;
            $typeProject->zip_code =  $zip_code;
            $typeProject->npwp =  $npwp;
            $typeProject->email =  $email;
            $typeProject->phone =  $phone;
            $typeProject->coa_code =  $coa_code;
            $typeProject->created_by = Auth::user()->username;
            $typeProject->save();

            // Session::flash('error', `Data Berhasil Disimpan`);

            return response()->redirectToRoute("r_supplier.index")->with("success", "Data $code Succesfully Created");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_supplier.index")->with("error", $th->getMessage());
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
        if ($request->ajax()){
            $dataProjectType = Supplier::query()->where("code", $id)->first();

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
            $address = $request->post("address");
            $zip_code = $request->post("zip_code");
            $npwp = $request->post("npwp");
            $email = $request->post("email");
            $phone = $request->post("phone");
            $coa_code = $request->post("coa_code");
            $getRole = Supplier::find($id );

            $getRole->name = $name;
            $getRole->coa_code = $coa_code;
            $getRole->address = $address;
            $getRole->zip_code = $zip_code;
            $getRole->npwp = $npwp;
            $getRole->email = $email;
            $getRole->phone = $phone;
            $getRole->coa_code = $coa_code;
            $getRole->updated_by = Auth::user()->username;
            $getRole->update();

            return response()->redirectToRoute("r_supplier.index")->with("success", "Data ".$name." Successfully Updated");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_supplier.index")->with("error", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Supplier::where("code",$id )->delete();

            return response()->redirectToRoute("r_supplier.index")->with("success", "Data Successfully Deleted");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return $this->errorException($th, "r_supplier.index");
        }
    }
}
