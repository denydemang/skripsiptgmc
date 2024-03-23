<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_Detail extends Model
{
    use HasFactory;
    protected $table = "project_details";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'project_code',
        'item_code',
        'unit_code',
        'qty',
        'price',
        'total',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
