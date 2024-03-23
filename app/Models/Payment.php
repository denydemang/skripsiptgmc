<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $primaryKey = "bkk_no";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'bkk_no',
        'transaction_date',
        'supplier_code',
        'ref_no',
        'coa_cash_code',
        'total_amount',
        'terbilang',
        'is_approve',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
