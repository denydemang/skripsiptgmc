<?php

namespace App\Http\Controllers;

use App\Models\ProjectRealisation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProjectRealisationController extends AdminController
{
    public function getProjectRealisationSearchtable(Request $request,  DataTables $dataTables){
        if ($request->ajax()){


            $startDate =Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $pr = ProjectRealisation::where("is_approve", 1)
            ->join('projects', "projects.code", "=", "project_realisations.project_code")
            ->select("project_realisations.*", "projects.name as project_name")
            ->where("project_realisations.customer_code",  $request->customer_code)
            ->whereBetween('project_realisations.realisation_date', [$startDate,$endDate])
            ->whereNotIn('project_realisations.code', function($query){
                $query->select('project_realisation_code')
                ->from('invoices');
            });

            return $dataTables->of($pr)
            ->editColumn('realisation_date', function($row) {
                return $row->realisation_date ? Carbon::parse($row->realisation_date)->format('d/m/Y') : '';
            })
            ->filterColumn('project_name', function($query, $keyword) {
                $query->whereRaw("porjects.name LIKE ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($row) {

                return '
                <div class="d-flex justify-content-center">
                    <button class="btn btn-sm btn-success selectprojectrealisation" data-name="'.$row->project_name.'" data-amount="'.$row->realisation_amount.'" data-code="'.$row->code.'" title="Select"><i class="fa fa-check"></i> Select</button>
                </div>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);

        }
    }
}
