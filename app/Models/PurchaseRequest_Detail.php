<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest_Detail extends Model
{
    use HasFactory;
    protected $table = "purchase_request_details";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'pr_no',
        'item_code',
        'unit_code',
        'qty',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
