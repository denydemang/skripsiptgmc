<?php

namespace App\Http\Controllers;

use App\Models\ProjectDetailB;
use Illuminate\Http\Request;

class ProjectDetailBController extends AdminController
{
    public function getDetailB($id = null) {
      
        return ProjectDetailB::join("projects",'project_detail_b.project_code', '=', 'projects.code')
        ->join("upah",'project_detail_b.upah_code', '=', 'upah.code')
        ->select("project_detail_b.id as project_detail_b_id", "projects.code as project_code",
        "projects.name as project_name", "upah.code as upah_code", "upah.job as job", "upah.unit as unit",
        "project_detail_b.qty" , "project_detail_b.price", "project_detail_b.total", "project_detail_b.created_by",
        "project_detail_b.updated_by" , "project_detail_b.created_at", "project_detail_b.updated_at"  )
        ->when($id !== null, function($query) use($id){
            $query->where('project_detail_b.project_code',$id);
        });
 
}
}
