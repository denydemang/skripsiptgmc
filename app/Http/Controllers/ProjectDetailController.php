<?php

namespace App\Http\Controllers;

use App\Models\Project_Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectDetailController extends AdminController
{
    public function getDetail($id = null) {
      
            // return Project_Detail::join("projects",'project_details.project_code', '=', 'projects.code')
            // ->join("items",'project_details.item_code', '=', 'items.code')
            // ->join("units" ,'project_details.unit_code', '=', 'units.code' )
            // ->leftjoin("stocks", 'project_details.item_code', '=' , 'stocks.item_code')
            // ->select("project_details.id as project_detail_id", "projects.code as project_code",
            // "projects.name as project_name",  "items.code as item_code", "items.name as item_name",
            // "units.code as unit_code", DB::raw('IFNULL(SUM(stocks.actual_stock - stocks.used_stock), 0) As stocks'),"project_details.qty" , "project_details.created_by",
            // "project_details.updated_by" , "project_details.created_at", "project_details.updated_at"  )
            // ->groupBy('project_details.id', 'items.code', 'items.name', 'units.code', 'project_details.qty', 'projects.code', 
            // 'projects.name', "project_details.created_by",  "project_details.updated_by", "project_details.created_at" , "project_details.updated_at")
            // ->when($id !== null, function($query) use($id){
            //     $query->where('project_details.project_code',$id);
            // });

            return DB::select("
                SELECT

                project_details.id as project_detail_id,
                projects.code as project_code,
                projects.name as project_name,
                items.code as item_code,
                items.name as item_name,
                units.code as unit_code,
                project_details.qty,

                (SELECT SUM(stocksavg.actual_stock) - SUM(stocksavg.used_stock) from stocksavg where stocksavg.item_code =items.code ) as stocks
                FROM

                project_details 
                INNER JOIN projects on project_details.project_code = projects.`code`
                INNER JOIN items on project_details.item_code = items.`code`
                INNER JOIN units on project_details.unit_code = units.`code`

                where project_details.project_code = ?
            
            ", [$id]);
            // Project_Detail::join("projects",'project_details.project_code', '=', 'projects.code')
            // ->join('items', 'items.code', "=", 'type_projects_details.item_code')
            // ->join("units" ,'project_details.unit_code', '=', 'units.code' )
            // ->join('stocksout_avg', function($join) {
            //     $join->on('project_details.item_code', '=', 'stocksout_avg.item_code');
            //     $join->on('project_details.project_code', '=', 'stocksout_avg.ref_no');
            // })
            // ->join()
            // ->select("project_details.id as project_detail_id", "projects.code as project_code", 
            // "projects.name as project_name",  "items.code as item_code", "items.name as item_name",
            // "units.code as unit_code",
            // DB::raw('IFNULL(SUM(stocksavg.actual_stock) - SUM(stocksavg.used_stock), 0) As stocks'), "project_details.qty" , "project_details.created_by",
            // "project_details.updated_by" , "project_details.created_at", "project_details.updated_at" )
            // ->groupBy('type_projects_details.item_code','items.name' ,  "type_projects_details.unit_code")
            // ->where('type_projects_details.type_project_code', $id)->get();
     
    }
}
