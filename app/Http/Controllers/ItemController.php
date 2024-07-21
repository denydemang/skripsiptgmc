<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StocksAVG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ItemController extends AdminController
{
    public function index(Request $request)
    {
        $supplyData = [
            'title' => 'Items',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),


        ];

        return response()->view("admin.master.item", $supplyData);
    }

    public function getDataitems(Request $request, DataTables $dataTables)
    {
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
                ->editColumn('min_stock', function($row) {
                    return floatval($row->min_stock);
                })
                ->editColumn('max_stock', function($row) {
                    return floatval($row->max_stock);
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
        $exist_item = Item::find($request->code);
        if (isset($exist_item)) {
            return response()->redirectToRoute("r_item.index")->with("error", "Code <b>" . $request->code . "</b> Sudah tersedia");
        } else {
            try {

                $supplyModel = Item::orderBy("code", "desc")->lockForUpdate()->first();
                $code_auto = $this->automaticCode("ITEM", $supplyModel, false, "code");
                $code = $request->post("code");
                $name = $request->post("name");
                $unit_code = $request->post("unit_code");
                $min_stock = $request->post("min_stock");
                $max_stock = $request->post("max_stock");
                $category_code = $request->post("category_code");


                $typeProject = new Item();
                $typeProject->code = $code!='' ? $code : $code_auto;
                $typeProject->name =  $name;
                $typeProject->unit_code =  $unit_code;
                $typeProject->min_stock =  $min_stock;
                $typeProject->max_stock =  $max_stock;
                $typeProject->category_code =  $category_code;
                $typeProject->created_by = Auth::user()->username;
                $typeProject->save();

                // Session::flash('error', `Data Berhasil Disimpan`);

                return response()->redirectToRoute("r_item.index")->with("success", "Data ".$typeProject->code." Succesfully Created");
            } catch (\Throwable $th) {
                // Session::flash('error', $th->getMessage());
                return response()->redirectToRoute("r_item.index")->with("error", $th->getMessage());
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

            $getRole = Item::find($id);
            $getRole->name = $name;
            $getRole->unit_code = $unit_code;
            $getRole->min_stock = $min_stock;
            $getRole->max_stock = $max_stock;
            $getRole->category_code = $category_code;
            $getRole->updated_by = Auth::user()->username;
            $getRole->update();

            return response()->redirectToRoute("r_item.index")->with("success", "Data " . $name . " Successfully Updated");
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
            Item::where("code", $id)->delete();

            // return response()->redirectToRoute("r_item.index")->with("success", "Data " . $id . " Successfully Deleted");
            return response()->json(['msg' => 'Data '.$id.' Successfully Deleted', 'status' => 'success', 'code' => 200]);

        } catch (\Throwable $th) {
            // Session::flash('error', $th->getMessage());
            // return response()->redirectToRoute("r_item.index")->with("error", $th->getMessage());
            return $this->errorException($th,"r_item.index", $id );
        }
    }

    public function getTableItemSearch(Request $request, DataTables $dataTables)
    {

        if ($request->Ajax()) {

            // $items = Item::leftjoin('stocks', "items.code", "=", "stocks.item_code")
            //     ->leftjoin('categories', 'items.category_code', '=', 'categories.code')
            //     ->select(
            //         'items.code',
            //         'items.name',
            //         'items.unit_code',
            //         'categories.code as category_code',
            //         'categories.name as category_name',
            //         'items.min_stock',
            //         'items.max_stock',
            //         DB::raw('IFNULL(SUM(stocks.actual_stock - stocks.used_stock), 0) As stocks')
            //     )
            //     ->groupBy('items.code', 'items.name', 'items.unit_code', 'categories.code', 'categories.name', 'items.min_stock', 'items.max_stock');

                $items = StocksAVG::Rightjoin("items", "stocksavg.item_code", "=", "items.code")
                ->join('categories', "categories.code", "=", "items.category_code")
                ->join('units', "units.code", "=", "items.unit_code")
                ->select('items.code' ,'items.name',  'units.code as unit_code','categories.code as category_code', 'categories.name as category_name', 'items.min_stock', 'items.max_stock', DB::raw('ifnull(sum(stocksavg.actual_stock) - sum(stocksavg.used_stock) ,0) as stocks'))
                ->groupBy('items.code', 'items.name' ,'categories.code','categories.name' ,'units.code', 'items.min_stock','items.max_stock');
            return $dataTables->of($items)
                ->addColumn('action', function ($row) {

                    $data = '<div class="d-flex justify-content-center">
                    <button class="btn btn-sm btn-success selectitembtn" data-stocks="' . $row->stocks . '" data-min_stock="' . $row->min_stock . '" data-max_stock="' . $row->max_stock . '" data-unit="' . $row->unit_code . '" data-code="' . $row->code . '"  data-name="' . $row->name . '" title="Select Item"><i class="fa fa-check"></i> Select</button>
                    </div>';

                    return $data;
                })
                ->editColumn('stocks', function ($row) {
                    return (float)$row->stocks;
                })
                ->editColumn('min_stock', function ($row) {
                    return (float)$row->min_stock;
                })
                ->editColumn('max_stock', function ($row) {
                    return (float)$row->max_stock;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }
}
