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
        'adr_no',
        'transaction_date',
        'customer_code',
        'coa_kredit',
        'coa_debit',
        'deposit_amount',
        'deposit_allocation',
        'description',
        'is_approve',
        'approved_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
