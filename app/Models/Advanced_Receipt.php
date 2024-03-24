<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advanced_Receipt extends Model
{
    use HasFactory;
    protected $table = "advanced_receipts";
    protected $primaryKey = "adr_no";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'code',
        'project_code',
        'item_code',
        'unit_code',
        'qty_estimated',
        'qty_used',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
