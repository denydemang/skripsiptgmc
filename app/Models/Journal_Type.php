<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal_Type extends Model
{
    use HasFactory;
    protected $table = "journal_types";
    protected $primaryKey = "code";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'code',
        'name',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
