<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $primaryKey = "code";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'code',
        'name',
        'project_type_code',
        'customer_code',
        'location',
        'budget',
        'start_date',
        'end_date',
        'project_status',
        'project_document',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
