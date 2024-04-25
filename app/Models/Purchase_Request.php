<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_Request extends Model
{
    use HasFactory;

    protected $table = "purchase_requests";
    protected $primaryKey = "pr_no";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'pr_no',
        'transaction_date',
        'pic_name',
        'division',
        'ref_no',
        'description',
        'is_approve',
        'is_purchased',
        'approved_by',
        'date_need',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}

