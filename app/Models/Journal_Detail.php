<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal_Detail extends Model
{
    use HasFactory;
    protected $table = "journal_details";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'voucher_no',
        'description',
        'coa_code',
        'debit',
        'credit',
        'created_by',
        'updated_by',
        'updated_at',
        'updated_by'
    ];
}
