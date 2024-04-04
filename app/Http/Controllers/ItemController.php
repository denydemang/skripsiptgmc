<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ItemController extends AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $supplyData = [
            'title' => 'Items',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),


        ];

        return response()->view("admin.master.item", $supplyData);
    }

    public function getDataitems(Request $request, DataTables $dataTables) {
        if ($request->ajax()) {

            $items = Item::query();


            // $users = User::query();

            return $dataTables->of($items)
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

            $supplyModel = Item::orderBy("code", "desc")->lockForUpdate()->first();
            $code = $this->automaticCode("I_" ,$supplyModel, false,"code");
            $name = $request->post("name");
            $unit_code = $request->post("unit_code");
            $min_stock = $request->post("min_stock");
            $max_stock = $request->post("max_stock");
            $category_code = $request->post("category_code");


            $typeProject = new Item();
            $typeProject->code = $code;
            $typeProject->name =  $name;
            $typeProject->unit_code =  $unit_code;
            $typeProject->min_stock =  $min_stock;
            $typeProject->max_stock =  $max_stock;
            $typeProject->category_code =  $category_code;
            $typeProject->created_by = Auth::user()->username;
            $typeProject->save();

            // Session::flash('error', `Data Berhasil Disimpan`);

            return response()->redirectToRoute("r_item.index")->with("success", "Data $code Succesfully Created");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_item.index")->with("error", $th->getMessage());
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
            $dataProjectType = Item::query()->where("code", $id)->first();

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
            $unit_code = $request->post("unit_code");
            $min_stock = $request->post("min_stock");
            $max_stock = $request->post("max_stock");
            $category_code = $request->post("category_code");

            $getRole = Item::find($id );
            $getRole->name = $name;
            $getRole->unit_code = $unit_code;
            $getRole->min_stock = $min_stock;
            $getRole->max_stock = $max_stock;
            $getRole->category_code = $category_code;
            $getRole->updated_by = Auth::user()->username;
            $getRole->update();

            return response()->redirectToRoute("r_item.index")->with("success", "Data ".$name." Successfully Updated");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_item.index")->with("error", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Item::where("code",$id )->delete();

            return response()->redirectToRoute("r_item.index")->with("success", "Data Successfully Deleted");
        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            return response()->redirectToRoute("r_item.index")->with("error", $th->getMessage());
        }
    }
}
