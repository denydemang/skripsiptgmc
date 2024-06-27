<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProjectDetail extends Model
{
    use HasFactory;

    protected $table = "type_projects_details";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;

    protected $guarded = [];

}
