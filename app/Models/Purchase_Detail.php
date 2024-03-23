<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_Detail extends Model
{
    use HasFactory;
    
    protected $table = "purchase_details";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'purchase_no',
        'item_code',
        'unit_code',
        'qty',
        'price',
        'total',
        'discount',
        'sub_total',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}
