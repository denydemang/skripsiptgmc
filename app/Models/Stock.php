<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'ref_no',
        'item_code',
        'unit_code',
        'item_date',
        'actual_stock',
        'used_stock',
        'cogs',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
