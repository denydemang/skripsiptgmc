<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class COA extends Model
{
    use HasFactory;
    protected $table = "coa";
    protected $primaryKey = "code";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'code',
        'name',
        'type',
        'level',
        'default_dk',
        'description',
        "beginning_balance",
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];


}
