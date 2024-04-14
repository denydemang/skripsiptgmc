<?php

namespace App\Http\Controllers;

use App\Models\Project_Detail_B_Realisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectDetailRealisationBController extends AdminController
{
    public function getDetailB($id = null) {
      
        return Project_Detail_B_Realisation::join("projects",'project_detail_b_realisations.project_code', '=', 'projects.code')
        ->join("upah",'project_detail_b_realisations.upah_code', '=', 'upah.code')
        ->select("project_detail_b_realisations.id as project_detail_b_realisations_id", "projects.code as project_code",
        "projects.name as project_name", "upah.code as upah_code", "upah.job as job", "upah.unit as unit", 
        "project_detail_b_realisations.qty_estimated" , "project_detail_b_realisations.qty_used", "project_detail_b_realisations.price", "project_detail_b_realisations.total",
        "project_detail_b_realisations.created_by","project_detail_b_realisations.updated_by" , 
        "project_detail_b_realisations.created_at", "project_detail_b_realisations.updated_at"  )
        ->when($id !== null, function($query) use($id){
            $query->where('project_detail_b_realisations.project_code',$id);
        });
    }

    public function getDetailDifference($id = null) {
        return Project_Detail_B_Realisation::join("projects",'project_detail_b_realisations.project_code', '=', 'projects.code')
        ->join("upah",'project_detail_b_realisations.upah_code', '=', 'upah.code')
        ->select("project_detail_b_realisations.id as project_detail_b_realisations_id", "projects.code as project_code",
        "projects.name as project_name", "upah.code as upah_code", "upah.job as job", "upah.unit as unit", 
        "project_detail_b_realisations.qty_estimated" , "project_detail_b_realisations.qty_used", "project_detail_b_realisations.price",
        DB::raw("ifnull(project_detail_b_realisations.qty_estimated * project_detail_b_realisations.price,0) as total_estimated") ,
        "project_detail_b_realisations.total",
        "project_detail_b_realisations.created_by","project_detail_b_realisations.updated_by" , 
        "project_detail_b_realisations.created_at", "project_detail_b_realisations.updated_at"  )
        ->when($id !== null, function($query) use($id){
            $query->where('project_detail_b_realisations.project_code',$id);
            $query->whereColumn("project_detail_b_realisations.qty_estimated", "!=" ,"project_detail_b_realisations.qty_used");
        });
    }
}
