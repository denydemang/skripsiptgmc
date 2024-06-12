<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash_Receive extends Model
{
    use HasFactory;
    protected $table = "cash_receives";
    protected $primaryKey = "bkm_no";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'bkm_no',
        'transaction_date',
        'customer_code',
        'ref_no',
        'received_via',
        'coa_cash_code',
        'cash_amount',
        'deposit_amount',
        'total_amount',
        'terbilang',
        'description',
        'is_approve',
        'approved_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
