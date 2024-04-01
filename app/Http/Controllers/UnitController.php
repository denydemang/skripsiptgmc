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
            'title' => 'Users Type',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),


        ];

        return response()->view("admin.master.unit", $supplyData);
    }

    public function getDataUnits(Request $request, DataTables $dataTables) {
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
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
