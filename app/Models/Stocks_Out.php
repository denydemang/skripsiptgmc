<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks_Out extends Model
{
    use HasFactory;
    protected $table = "stocks_out";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'ref_no',
        'item_code',
        'unit_code',
        'item_date',
        'qty',
        'cogs',
        'stock_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}
