<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_Project extends Model
{
    use HasFactory;

    protected $table = "type_projects";
    protected $primaryKey = "code";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'code',
        'name',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
