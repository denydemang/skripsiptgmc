<?php

namespace App\Http\Controllers;

use App\Models\Project_Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectDetailController extends AdminController
{
    public function getDetail($id = null) {
      
            return Project_Detail::join("projects",'project_details.project_code', '=', 'projects.code')
            ->join("items",'project_details.item_code', '=', 'items.code')
            ->join("units" ,'project_details.unit_code', '=', 'units.code' )
            ->leftjoin("stocks", 'project_details.item_code', '=' , 'stocks.item_code')
            ->select("project_details.id as project_detail_id", "projects.code as project_code",
            "projects.name as project_name",  "items.code as item_code", "items.name as item_name",
            "units.code as unit_code", DB::raw('IFNULL(SUM(stocks.actual_stock - stocks.used_stock), 0) As stocks'),"project_details.qty" , "project_details.created_by",
            "project_details.updated_by" , "project_details.created_at", "project_details.updated_at"  )
            ->groupBy('project_details.id', 'items.code', 'items.name', 'units.code', 'project_details.qty', 'projects.code', 
            'projects.name', "project_details.created_by",  "project_details.updated_by", "project_details.created_at" , "project_details.updated_at")
            ->when($id !== null, function($query) use($id){
                $query->where('project_details.project_code',$id);
            });
     
    }
}
