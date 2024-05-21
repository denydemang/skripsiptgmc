<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBook_DetailB extends Model
{
    
    use HasFactory;
    protected $table = "cash_books_detail_b";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'cash_no',
        'coa',
        'transaction_date',
        'ref',
        'description',
        'debit',
        'credit',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
