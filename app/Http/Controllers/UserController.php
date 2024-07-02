<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends AdminController
{
    public function login(Request $request)
    {

        $username = $request->post("username");
        $pw = $request->post("password");

        $isAuthorised = Auth::attempt([
            "username" => $username,
            "pw" =>  $pw
        ], true);

        if ($isAuthorised) {
            return true;
        } else {
            return false;
        }
    }

    function getViewUsers(Request $request)
    {
        $supplyData = [
            'title' => 'Users',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),


        ];

        return response()->view("admin.master.users", $supplyData);
    }

    public function getDataUsers(Request $request, DataTables $dataTables)
    {
        if ($request->ajax()) {

            $users = User::join('roles', 'users.id_role', '=', 'roles.id')
                ->select('users.*', 'roles.name as role_name');


            // $users = User::query();

            return $dataTables->of($users)

                ->editColumn('active_status', function ($row) {
                    return $row->active_status == 0 ? "<span class='badge badge-danger'>Pasif</span>" : "<span class='badge badge-primary'>Active</span>";
                })

                ->addColumn('action', function ($row) {

                    return '
                <div class="d-flex justify-content-center">
                <button class="btn btn-sm btn-primary editbtn" data-username="' . $row->username . '" title="Edit"><i class="fa fa-edit"></i></button>
                <button class="btn btn-sm btn-danger deletebtn" data-username="' . $row->username . '" title="Delete"><i class="fa fa-trash"></i></button>
                </div>';
                })
                ->rawColumns(['action', 'active_status'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    function addUsers(Request $request) {
        $exist_user = User::find($request->username);
        if (isset($exist_user)) {
            return response()->redirectToRoute("admin.users")->with("error", "Username <b>".$request->username."</b> Sudah tersedia");
        } else {

            User::create([
                "username" => $request->username,
                "name" => $request->name,
                "password" => Hash::make($request->password),
                "id_role" => $request->role,
                "active_status" => 0,
                "created_by" => Auth::user()->username,
                "updated_by" => Auth::user()->username,

            ]);

            return response()->redirectToRoute("admin.users")->with("success", "Username <b>".$request->username."</b> Berhasil Terbuat");
        }

    }

    public function editDataUsers($username, Request $request){

        if ($request->ajax()){
            $dataProjectType = User::query()->where("username", $username)->first();

            return json_encode($dataProjectType);

        }

    }

    public function updateUser(Request $request){
        try {
            $username =  $request->post("username");
            $name = $request->post("name");
            $password = Hash::make($request->post("password"));
            $role = $request->post("role");

            $getUser = User::where("username", $username )->first();

            $getUser->username = $username;
            $getUser->name = $name;
            $getUser->password = $password!='' ? $password : $getUser->password ;
            $getUser->id_role = $role;
            $getUser->updated_by = Auth::user()->username;
            $getUser->update();
            // Session::flash('error', `Data Berhasil Disimpan`);

            return response()->redirectToRoute("admin.users")->with("success", "Data ".$username." Successfully Updated");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("admin.users")->with("error", $th->getMessage());
        }


    }

    public function deleteUser($username){
        try {
            User::where("username",$username )->delete();

            // return response()->redirectToRoute("admin.users")->with("success", "Data ".$username." Successfully Deleted");
            return response()->json(['msg' => 'Data '.$username.' Successfully Deleted', 'status' => 'success', 'code' => 200]);

        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            // return response()->redirectToRoute("admin.users")->with("error", $th->getMessage());
            return $this->errorException($th,"admin.users", $username );
        }


    }

}
