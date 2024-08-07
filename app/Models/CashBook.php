<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBook extends Model
{
    use HasFactory;
    protected $table = "cash_books";
    protected $primaryKey = "cash_no";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'cash_no',
        'transaction_date',
        'COA_Cash',
        'ref',
        'total_transaction',
        'description',
        'CbpType',
        'is_approve',
        'approved_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
