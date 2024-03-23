<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $primaryKey = "code";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'code',
        'name',
        'coa_code',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
