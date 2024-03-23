<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $primaryKey = "voucher_no";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'voucher_no',
        'transaction_date',
        'ref_no',
        'journal_type_code',
        'posting_status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
