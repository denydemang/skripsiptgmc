<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class RoleController extends AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $supplyData = [
            'title' => 'Users Type',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),


        ];

        return response()->view("admin.master.role", $supplyData);
    }

    public function getDataroles(Request $request, DataTables $dataTables) {
        if ($request->ajax()) {

            $roles = Role::query();


            // $users = User::query();

            return $dataTables->of($roles)
                ->addColumn('action', function ($row) {

                    return '
                <div class="d-flex justify-content-center">
                <button class="btn btn-sm btn-primary editbtn" data-id="' . $row->id . '" title="Edit"><i class="fa fa-edit"></i></button>
                <button class="btn btn-sm btn-danger deletebtn" data-id="' . $row->id . '" title="Delete"><i class="fa fa-trash"></i></button>
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
            Role::create([
                "name" => $request->name,
                "active_status" => 1,
                "created_by" => Auth::user()->username,
                "updated_by" => Auth::user()->username,
            ]);
            return response()->redirectToRoute("r_role.index")->with("success", "Data ".$request->name." Successfully Updated");
        } catch (\Throwable $th) {
            return response()->redirectToRoute("r_role.index")->with("error", $th->getMessage());

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
            $dataProjectType = Role::query()->where("id", $id)->first();

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
            $getRole = Role::find($id );
            $getRole->name = $name;
            $getRole->updated_by = Auth::user()->username;
            $getRole->update();

            return response()->redirectToRoute("r_role.index")->with("success", "Data ".$name." Successfully Updated");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_role.index")->with("error", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Role::where("id",$id )->delete();

            return response()->redirectToRoute("r_role.index")->with("success", "Data Successfully Deleted");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_role.index")->with("error", $th->getMessage());
        }
    }
}
