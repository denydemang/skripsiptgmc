<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetailB extends Model
{
    use HasFactory;
    protected $table = "project_detail_b";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'code',
        'project_code',
        'upah_code', 
        'unit',
        'price',
        'total',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
