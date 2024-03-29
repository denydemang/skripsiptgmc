<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    
    protected $table = "purchases";
    protected $primaryKey = "purchase_no";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'purchase_no',
        'pr_no',
        'transaction_date',
        'supplier_code',
        'total',
        'other_fee',
        'percent_ppn',
        'ppn_amount',
        'grand_total',
        'payment_term_code',
        'is_credit',
        'is_approve',
        'paid_amount',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
