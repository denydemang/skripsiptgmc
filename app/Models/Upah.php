<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upah extends Model
{
    use HasFactory;

    protected $table = "upah";
    protected $primaryKey = "code";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'code',
        'job',
        'description',
        'unit',
        'price',
        'coa_code',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
