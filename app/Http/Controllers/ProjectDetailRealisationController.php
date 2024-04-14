<?php

namespace App\Http\Controllers;

use App\Models\Project_Detail_Realisations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectDetailRealisationController extends AdminController
{
    
    public function getDetail($id = null) {
      
        return Project_Detail_Realisations::join("projects",'project_detail_realisations.project_code', '=', 'projects.code')
        ->join("items",'project_detail_realisations.item_code', '=', 'items.code')
        ->join("units" ,'project_detail_realisations.unit_code', '=', 'units.code' )
        ->leftjoin("stocks", 'project_detail_realisations.item_code', '=' , 'stocks.item_code')
        ->select("project_detail_realisations.id as project_detail_realisation_id", "projects.code as project_code",
        "projects.name as project_name",  "items.code as item_code", "items.name as item_name",
        "units.code as unit_code", DB::raw('IFNULL(SUM(stocks.actual_stock - stocks.used_stock), 0) As stocks'),
        "project_detail_realisations.qty_estimated" , "project_detail_realisations.qty_used" , "project_detail_realisations.created_by",
        "project_detail_realisations.updated_by" , "project_detail_realisations.created_at", "project_detail_realisations.updated_at"  )
        ->groupBy('project_detail_realisations.id', 'items.code', 'items.name', 'units.code', 'project_detail_realisations.qty_estimated', 
        "project_detail_realisations.qty_used",'projects.code', 'projects.name', 
        "project_detail_realisations.created_by",  "project_detail_realisations.updated_by", "project_detail_realisations.created_at" , 
        "project_detail_realisations.updated_at")
        ->when($id !== null, function($query) use($id){
            $query->where('project_detail_realisations.project_code',$id);
        });
 
    }

    public function getDetailDifference($id = null) {
      
        return Project_Detail_Realisations::join("projects",'project_detail_realisations.project_code', '=', 'projects.code')
        ->join("items",'project_detail_realisations.item_code', '=', 'items.code')
        ->join("units" ,'project_detail_realisations.unit_code', '=', 'units.code' )
        ->leftjoin("stocks", 'project_detail_realisations.item_code', '=' , 'stocks.item_code')
        ->select("project_detail_realisations.id as project_detail_realisation_id", "projects.code as project_code",
        "projects.name as project_name",  "items.code as item_code", "items.name as item_name",
        "units.code as unit_code", DB::raw('IFNULL(SUM(stocks.actual_stock - stocks.used_stock), 0) As stocks'),
        "project_detail_realisations.qty_estimated" , "project_detail_realisations.qty_used" , "project_detail_realisations.created_by",
        "project_detail_realisations.updated_by" , "project_detail_realisations.created_at", "project_detail_realisations.updated_at"  )
        ->groupBy('project_detail_realisations.id', 'items.code', 'items.name', 'units.code', 'project_detail_realisations.qty_estimated', 
        "project_detail_realisations.qty_used",'projects.code', 'projects.name', 
        "project_detail_realisations.created_by",  "project_detail_realisations.updated_by", "project_detail_realisations.created_at" , 
        "project_detail_realisations.updated_at")
        ->when($id !== null, function($query) use($id){
            $query->where('project_detail_realisations.project_code',$id);
            $query->whereColumn("project_detail_realisations.qty_estimated", "!=" ,"project_detail_realisations.qty_used");
        });

 
}

}
