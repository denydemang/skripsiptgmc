<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CustomerController extends AdminController
{
    public function index(Request $request)
    {
        $supplyData = [
            'title' => 'Customers',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),


        ];

        return response()->view("admin.master.customer", $supplyData);
    }

    public function getDatacustomers(Request $request, DataTables $dataTables)
    {
        if ($request->ajax()) {

            $customers = Customer::query();


            // $users = User::query();

            return $dataTables->of($customers)
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
        $exist_customer = Customer::find($request->code);
        if (isset($exist_customer)) {
            return response()->redirectToRoute("r_customer.index")->with("error", "Code <b>" . $request->code . "</b> Sudah tersedia");
        } else {
            try {

                $supplyModel = Customer::orderBy("code", "desc")->lockForUpdate()->first();
                $code_auto = $this->automaticCode("CUST", $supplyModel, false, "code");
                $code = $request->post("code");
                $name = $request->post("name");
                $address = $request->post("address");
                $zip_code = $request->post("zip_code");
                $npwp = $request->post("npwp");
                $email = $request->post("email");
                $phone = $request->post("phone");
                $coa_code = $request->post("coa_code");


                $typeProject = new Customer();
                $typeProject->code = $code!='' ? $code : $code_auto;
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

                return response()->redirectToRoute("r_customer.index")->with("success", "Data $code Succesfully Created");
            } catch (\Throwable $th) {
                // Session::flash('error', $th->getMessage());
                return response()->redirectToRoute("r_customer.index")->with("error", $th->getMessage());
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
            $dataProjectType = Customer::query()->where("code", $id)->first();

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
            $getRole = Customer::find($id);

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

            return response()->redirectToRoute("r_customer.index")->with("success", "Data " . $name . " Successfully Updated");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_customer.index")->with("error", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Customer::where("code", $id)->delete();

            return response()->redirectToRoute("r_customer.index")->with("success", "Data ".$id." Successfully Deleted");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_customer.index")->with("error", $th->getMessage());
        }
    }

    public function getDataCustomerForModal(Request $request, DataTables $dataTables)
    {


        if ($request->ajax()) {


            $customers = Customer::query();


            return $dataTables->of($customers)
                ->addColumn('action', function ($row) {

                    return '
                <div class="d-flex justify-content-center">
                    <button class="btn btn-sm btn-success selectcustomerbtn" data-code="' . $row->code . '"  data-name="' . $row->name . '" title="Select"><i class="fa fa-check"></i> Select</button>
                </div>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }
}
