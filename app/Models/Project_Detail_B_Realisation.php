<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_Detail_B_Realisation extends Model
{
    use HasFactory;
    protected $table = "project_detail_b_realisations";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'project_code',
        'upah_code', 
        'unit',
        'qty_estimated',
        'qty_used',
        'price',
        'total',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
