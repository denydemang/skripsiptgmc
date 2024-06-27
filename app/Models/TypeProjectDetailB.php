<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProjectDetailB extends Model
{
    use HasFactory;

    protected $table = "type_projects_details_b";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;

    protected $guarded = [];
}
