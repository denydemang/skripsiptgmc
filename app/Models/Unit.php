<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $primaryKey = "code";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
