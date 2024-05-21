<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBook_Detail extends Model
{
    use HasFactory;
    protected $table = "cash_book_details";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'cash_no',
        'coa',
        'description',
        'amount',
        'CbpType',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
