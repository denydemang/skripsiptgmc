<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_Detail_Realisations extends Model
{
    use HasFactory;
    protected $table = "project_detail_realisations";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'project_realisation_code',
        'item_code', 
        'unit_code',
        'qty_addtional',
        'qty_used',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
