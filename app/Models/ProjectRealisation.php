<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRealisation extends Model
{
    use HasFactory;
    protected $primaryKey = "code";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'code',
        'realisation_date',
        'project_code',
        'customer_code',
        'description',
        'project_amount',
        'percent_realisation',
        'realisation_amount',
        'description',
        'is_approve',
        'approved_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

}
